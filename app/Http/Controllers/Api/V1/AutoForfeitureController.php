<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\AutoForfeiture\Services\AutoForfeitureService;
use App\Domain\AutoForfeiture\Services\ForfeitureLineService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AutoForfeitureController extends Controller
{
    public function __construct(
        protected AutoForfeitureService $service,
        protected ForfeitureLineService $service2
    ) {}

    public function index()
    {
        return response()->json(
            $this->service->list(),
            $this->service2->list()
        );
    }


    public function readGoogleSheet()
    {
        $sheetId = '1fzhtTYuifG4_RNd200WyMjTD0a5RkjuWU9Vf2Hm2wXw';
        $gid = 0;
        $url = "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv&gid={$gid}";
        $csv = file_get_contents($url);

        if ($csv === false) {
            abort(500, 'Unable to read Google Sheet');
        }

        $lines = explode("\n", trim($csv));
        $sheetDocs = [];
        foreach (array_slice($lines, 1) as $line) {
            $row = str_getcsv($line);
            if (isset($row[4]) && !empty($row[4])) {
                $sheetDocs[] = "'" . addslashes($row[4]) . "'";
            }
        }

        // If empty, put a dummy value to avoid SQL error
        if (empty($sheetDocs)) {
            $sheetDocs[] = "''";
        }

        // Join as a comma-separated string for SQL
        $notInList = implode(',', $sheetDocs);

        return response()->json($notInList);
    }

    public function getAgedData()
    {
        // 1. Read Google Sheet
        $sheetId = '1fzhtTYuifG4_RNd200WyMjTD0a5RkjuWU9Vf2Hm2wXw';
        $gid = 0;
        $url = "https://docs.google.com/spreadsheets/d/{$sheetId}/export?format=csv&gid={$gid}";
        $csv = file_get_contents($url);

        if ($csv === false) {
            abort(500, 'Unable to read Google Sheet');
        }

        $lines = explode("\n", trim($csv));
        $sheetDocs = [];
        foreach (array_slice($lines, 1) as $line) {
            $row = str_getcsv($line);
            if (isset($row[4]) && !empty($row[4])) {
                $sheetDocs[] = "'" . addslashes($row[4]) . "'";
            }
        }

        // If empty, put a dummy value to avoid SQL error
        if (empty($sheetDocs)) {
            $sheetDocs[] = "''";
        }

        // Join as a comma-separated string for SQL
        $notInList = implode(',', $sheetDocs);

        // 2. Your query with NOT IN
        $query = "
    SELECT
    MAX(agePay.name1) AS name1,
    MAX(agePay.mp_l_preownership_id) AS mp_l_preownership_id,
    MAX(agePay.is_owned) AS is_owned,
    MAX(agePay.mp_i_owner_id) AS mp_i_owner_id,
    MAX(agePay.mp_i_lot_id) AS mp_i_lot_id,
    MAX(agePay.is_status) AS is_status,
    MAX(agePay.documentno) AS documentno,
    MAX(agePay.lotID) AS lotID,
    MAX(agePay.reference) AS reference,
    MAX(agePay.date_of_payment) AS date_of_payment,
    SUM(agePay.amort_sales) AS amort_sales,
    SUM(agePay.amort_pcf) AS amort_pcf,
    SUM(agePay.amort_vat) AS amort_vat,
    SUM(agePay.dateRangebalance) AS dateRangebalance,
    MAX(agePay.ageDesc) AS ageDesc,
    MAX(agePay.amtunpaid) AS OB
FROM (
    SELECT
        bpar.name1,
        agr.docstatus AS is_status,
        preown.mp_l_preownership_id,
        preown.is_owned,
        preown.mp_i_owner_id,
        lot.mp_i_lot_id,
        CONCAT('RP-LSP-', lot.area_no, LPAD(lot.block_no, 2, '0'), LPAD(lot.lot_no, 3, '0')) AS reference,
        IFNULL(docT.documentno_pr, agr.documentno) AS documentno,
        CONCAT(lot.area_no, '-', lot.block_no, '-', lot.lot_no) AS lotID,
        breakdown.date_of_payment,
        IFNULL(breakdown.amt_amort_sales,0) - IFNULL(breakdown.amt_amort_sales_used,0) AS amort_sales,
        (IFNULL(breakdown.amt_amort,0) - IFNULL(breakdown.amt_amort_used,0)) * 0.1 AS amort_pcf,
        ROUND((IFNULL(breakdown.amt_amort,0) - IFNULL(breakdown.amt_amort_used,0)) * 0.9 / 1.12 * 0.12, 2) AS amort_vat,
        (IFNULL(breakdown.amt_amort,0) - IFNULL(breakdown.amt_amort_used,0)) AS dateRangebalance,
        IF(
            DATEDIFF(CURDATE(), breakdown.date_of_payment) < 0, 'Current',
            IF(
                DATEDIFF(CURDATE(), breakdown.date_of_payment) BETWEEN 0 AND 30, '0-30 DAYS',
                IF(
                    DATEDIFF(CURDATE(), breakdown.date_of_payment) BETWEEN 31 AND 60, '31-60 DAYS',
                    IF(
                        DATEDIFF(CURDATE(), breakdown.date_of_payment) BETWEEN 61 AND 90, '61-90 DAYS',
                        '90 DAYS OVER'
                    )
                )
            )
        ) AS ageDesc,
        preown.amtcontract - ROUND(
            IFNULL(preown.total_sales,0)
            + (IFNULL(preown.amt_transferred,0)*1.12/0.9)
            + IFNULL(preown.total_vat,0)
            + IFNULL(preown.total_pcf,0)
            + IFNULL(preown.total_discount,0)
            + (IFNULL(preown.amt_waived,0)*1.12/0.9), 2
        ) AS amtunpaid
    FROM mp_l_pre_ownership_future_pmt_breakdown AS breakdown
    INNER JOIN mp_l_preownership AS preown
        ON preown.mp_l_preownership_id = breakdown.mp_l_preowership_id
    INNER JOIN mp_i_owner AS owners
        ON owners.mp_i_owner_id = preown.mp_i_owner_id
    INNER JOIN bpar_i_person AS bpar
        ON owners.bpar_i_person_id = bpar.bpar_i_person_id
    INNER JOIN mp_i_lot AS lot
        ON lot.mp_i_lot_id = preown.mp_i_lot_id
    INNER JOIN mp_t_purchagr AS agr
        ON agr.mp_t_purchagr_id = preown.mp_t_purchagr_id
    LEFT JOIN doc_t_reference_number AS docT
        ON docT.doc_t_reference_number_id = agr.doc_t_reference_number_id
    WHERE DATE(breakdown.date_of_payment) <= CURDATE()
        AND breakdown.is_paid = 0
        AND preown.amtcontract > 0
        AND (preown.is_cancelled IS FALSE OR preown.is_cancelled IS NULL)
        AND (preown.is_forfeited IS NULL OR preown.is_forfeited IS FALSE)
) AS agePay
WHERE agePay.dateRangebalance > 0
    AND agePay.ageDesc = '90 DAYS OVER'
    AND agePay.is_status != 'LCK'
    AND agePay.amtunpaid > 5
    AND agePay.documentno NOT IN ($notInList)
GROUP BY agePay.mp_i_lot_id, agePay.ageDesc
    ";

        $results = DB::connection('mysql_secondary')->select($query);

        return response()->json($results);
    }
    //   $query = "SELECT 
    //             CAST(SUBSTRING(documentno_pr, 5) AS UNSIGNED) AS number_part
    //         FROM doc_t_reference_number
    //         WHERE documentno_pr LIKE 'NFFT%'
    //         ORDER BY date_draft DESC
    //         LIMIT 1;
    //         ";
    public function saveToDocTReference()
    {
        return DB::connection('mysql_secondary')->transaction(function () {

            // 1. Get next DR number
            $latest = DB::connection('mysql_secondary')
                ->table('doc_t_reference_number')
                ->selectRaw("CAST(SUBSTRING(documentno_dr, 5, LENGTH(documentno_dr) - 6) AS UNSIGNED) AS number_part")
                ->where('documentno_dr', 'like', 'NFFT%DR')
                ->orderByDesc('date_draft')
                ->lockForUpdate() // 🔒 prevents duplicate numbers
                ->first();

            $nextNumber = ($latest->number_part ?? 0) + 1;

            $documentNoDr = 'NFFT' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT) . 'DR';

            // 2. Insert reference number
            $referenceId = DB::connection('mysql_secondary')
                ->table('doc_t_reference_number')
                ->insertGetId([
                    'doc_i_submod_id' => DB::connection('mysql_secondary')
                        ->table('doc_i_submod')
                        ->where('submodule_code', 'CPU')
                        ->value('doc_i_submod_id'),

                    'documentno_dr' => $documentNoDr,
                    'date_draft'    => now(),
                    'ad_org_id'     => '162012',
                    'date_created'  => now(),
                    'created'       => 'System Auto Forfeited',
                    'is_active'     => 1,
                ]);

            return response()->json([
                'reference_id' => $referenceId,
                'documentno_dr' => $documentNoDr
            ]);
        });
    }

    public function saveToForfeiture(Request $request)
    {
        $data = $request->validate([
            'ad_org_id'                 => 'required|integer',
            'doc_i_submod_id'           => 'required|integer',
            'date_trans'                => 'nullable|date',
            'date_gl'                   => 'nullable|date',
            'docstatus'                 => 'nullable|string',
            'documentno'                => 'nullable|string',
            'mp_s_owner_id'             => 'required|integer',
            'doc_t_reference_number_id' => 'required|integer',
            'created'                   => 'nullable|string',
            'date_created'              => 'nullable|date',
            'updated'                   => 'nullable|date',
            'date_updated'              => 'nullable|date',
            'is_active'                 => 'nullable|boolean',
            'mp_i_owner_id'             => 'required|integer',
        ]);

        $forfeiture = $this->service->create($data);

        return response()->json([
            'forfeiture_id' => $forfeiture,
        ], 201);
    }

    public function saveToForfeitureLine(Request $request)
    {
        $data = $request->validate([
            'mp_t_lotforfeiture_id' => 'required|integer',
            'mp_l_preownership_id'  => 'nullable|integer',
            'amt_overdue'           => 'nullable|numeric',
            'amt_paid'              => 'nullable|numeric',
            'date_last_payment'     => 'nullable|date',
            'created'               => 'nullable|string',
            'updated'               => 'nullable|string',
            'is_active'             => 'nullable|boolean',
            'amt_overdue_sales'     => 'nullable|numeric',
            'amt_sales'             => 'nullable|numeric',
        ]);

        $forfeitureLineId = $this->service2->create($data);

        return response()->json([
            'forfeiture_id' => $forfeitureLineId,
        ], 201);
    }
}

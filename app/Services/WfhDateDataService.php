<?php

namespace App\Services;

use App\Models\Wfh;

class WfhDateDataService
{
    /**
     * Get WFH records for a given user where date is in $wfhDates.
     *
     * @param int $userId
     * @param array $wfhDates
     * @return array
     */
    public function getWfhDataByDates(int $userId, array $wfhDates): array
    {
        // ✅ Fetch all WFH records matching user_id and given dates
        $wfhRecords = Wfh::where('user_id', $userId)
            ->whereIn('date', $wfhDates)
            ->orderBy('date', 'asc')
            ->get(['date', 'percent', 'remark']); // only required fields
        


        // ✅ Prepare structured data
        return [
            'dates' => $wfhRecords->pluck('percent', 'date')->toArray(),
            'percentages' => $wfhRecords->pluck('percent')->toArray(),
        ];
    }
}

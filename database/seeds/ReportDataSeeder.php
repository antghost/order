<?php

use Illuminate\Database\Seeder;
use App\Models\ReportData;
use Carbon\Carbon;

class ReportDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //生成2010年至2017年每个月的报表数据
        DB::table('report_datas')->truncate();
        $reportData = new ReportData();
        for ($year = 2010; $year <= 2017; $year++){
            echo "creating ".$year." data".PHP_EOL;
            ($year == 2017) ? $max = 10 : $max = 12;
            for ($month = 1; $month <= $max; $month++){
                $dt = Carbon::create($year, $month);
                $startDate = $dt->copy()->startOfMonth();
                $endDate = $dt->copy()->endOfMonth();
                $reportData->userBookDays($year, $month, $startDate, $endDate);
            }
            echo $year." data created.".PHP_EOL;
        }
    }
}

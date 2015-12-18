<?php

namespace app\controllers;

use yii\web\Controller;
use yii;
use app\models\Report;
use miloschuman\highcharts\Highcharts;

class ReportController extends Controller {

    public $layout = "admin-lte";

    public function actionIndex() {
        
    }

    //รายงาน รายรับรายจ่าย ค่าขนส่งแยกตามประเภทรถ
    public function actionReport_income_expenses() {
        return $this->render('report_income_expenses', []);
    }

    public function actionLoad_report_income_expenses() {
        $report = new Report();
        $type = $report->Get_type();

        return $this->renderPartial('load_report_income_expenses', [
                    'type' => $type,
        ]);
    }

    public function actionReport_year() {
        return $this->render('report_year', [
        ]);
    }

    public function actionLoad_report_year() {
        $year = \Yii::$app->request->post('year');
        $report = new Report();
        $result = $report->Report_year($year);
        $chart = $this->actionChart_year($year);
        return $this->renderPartial('load_report_year', [
                    'report' => $result,
                    'year' => $year,
                    'chart' => $chart,
        ]);
    }

    public function actionChart_year($year = null) {

        $config = new \app\models\Config_system();
        $month = $config->MonthFull();
        $title = "ยอดกำไร - ขาดทุน ปี พ.ศ. ";
        $title .= ($year + 543);

        $labels = "รวมทั้งปี";
        $report = new Report();

        $result = $report->Report_year($year);

        $sum_expenses_row = 0;
        $sum_total_row = 0;
        $sum_income = 0;
        $sum_outcome = 0;
        $income_month = 0;
        foreach ($result as $rs):
            $sum_expenses_row = ($rs['oil_price'] + $rs['gas_price'] + $rs['expenses_around'] + $rs['fix_truck'] + $rs['income_driver'] + $rs['truck_period'] + $rs['truck_act']);
            $sum_total_row = ($rs['income'] - $sum_expenses_row);
            $income_month = $rs['income'];
            if (empty($income_month)) {
                $income = 0;
            } else {
                $income = $income_month;
            }

            if (empty($sum_expenses_row)) {
                $outcome = 0;
            } else {
                $outcome = $sum_expenses_row;
            }
            $value_income[] = (int) $income;
            $value_outcome[] = (int) $outcome;

            $sum_income = $sum_income + $rs['income']; //รายได้รวมทุกเดือน
            $sum_outcome = $sum_outcome + $sum_expenses_row; //รายจ่ายรวมทุกเดือน
        endforeach;

        //$val_income = implode('', $value_income);
        //$val_outcome = implode('', $value_outcome);

        return $this->renderAjax('chart', [
                    'category' => $month,
                    'title' => $title,
                    'labels' => $labels,
                    'val_income' => $value_income,
                    'val_outcome' => $value_outcome,
                    'sumIncome' => (int) $sum_income,
                    'sumOutcome' => (int) $sum_outcome,
        ]);
    }

    public function actionReport_month($year = null, $month = null) {
        $report = new Report();
        $result = $report->Report_month($year, $month);
        $chart = $this->actionChart_month($year, $month);
        return $this->render('report_month', [
                    'report' => $result,
                    'year' => $year,
                    'month' => $month,
                    'chart' => $chart,
        ]);
    }

    public function actionChart_month($year = null, $month = null) {
        $config = new \app\models\Config_system();
        $months = $config->MonthFullKey();
        $title = "ยอดกำไร - ขาดทุน ปี พ.ศ. ";
        $title .= ($year + 543);
        $title .= " เดือน " . $months[$month];
        $labels = "รวมทั้งเดือน";
        $report = new Report();

        $result = $report->Report_month($year, $month);

        $sum_expenses_row = 0;
        $sum_total_row = 0;
        $sum_income = 0;
        $sum_outcome = 0;
        $income_month = 0;
        foreach ($result as $rs):
            $day[] = $rs['DAY'];
            $sum_expenses_row = ($rs['oil_price'] + $rs['gas_price'] + $rs['expenses_around'] + $rs['fix_truck'] + $rs['income_driver'] + $rs['truck_period'] + $rs['truck_act']);
            $sum_total_row = ($rs['income'] - $sum_expenses_row);
            $income_month = $rs['income'];
            if (empty($income_month)) {
                $income = 0;
            } else {
                $income = $income_month;
            }

            if (empty($sum_expenses_row)) {
                $outcome = 0;
            } else {
                $outcome = $sum_expenses_row;
            }
            $value_income[] = (int) $income;
            $value_outcome[] = (int) $outcome;

            $sum_income = $sum_income + $rs['income']; //รายได้รวมทุกเดือน
            $sum_outcome = $sum_outcome + $sum_expenses_row; //รายจ่ายรวมทุกเดือน
        endforeach;

        //$val_income = implode('', $value_income);
        //$val_outcome = implode('', $value_outcome);

        return $this->renderAjax('chart', [
                    'category' => $day,
                    'title' => $title,
                    'labels' => $labels,
                    'val_income' => $value_income,
                    'val_outcome' => $value_outcome,
                    'sumIncome' => (int) $sum_income,
                    'sumOutcome' => (int) $sum_outcome,
        ]);
    }

    public function actionReport_period() {
        return $this->render('report_period');
    }

    public function actionLoad_report_period() {
        $year = \Yii::$app->request->post('year');
        $period = \Yii::$app->request->post('period');

        $report = new Report();
        $result = $report->Report_period($year, $period);

        return $this->renderPartial('load_report_period', [
                    'report' => $result,
                    'year' => $year,
                        //'chart' => $chart,
        ]);
    }

    public function actionChart_period() {
        //$config = new \app\models\Config_system();
        $year = \Yii::$app->request->post('year');
        $title = "ยอดกำไร - ขาดทุน ปี พ.ศ. ";
        $title .= ($year + 543);

        $p1 = $period1 = $this->actionGet_period($year, 1);
        $p2 = $period1 = $this->actionGet_period($year, 2);
        $p3 = $period1 = $this->actionGet_period($year, 3);
        $p4 = $period1 = $this->actionGet_period($year, 4);

        $val_income = array($p1['1'], $p2['1'], $p3['1'], $p4['1']);
        $val_outcome = array($p1['2'], $p2['2'], $p3['2'], $p4['2']);
        //$val_income = implode('', $value_income);
        //$val_outcome = implode('', $value_outcome);


        return $this->renderAjax('chart_period', [
                    'title' => $title,
                    'labels' => "จำนวน",
                    'val_income' => $val_income,
                    'val_outcome' => $val_outcome,
        ]);
    }

    public function actionGet_period($year = null, $period = null) {
        $report = new Report();

        $result = $report->Report_period($year, $period);

        $sum_expenses_row = 0;
        $sum_total_row = 0;
        $sum_income = 0;
        $sum_outcome = 0;
        //$income_month = 0;
        foreach ($result as $rs):
            $sum_expenses_row = ($rs['oil_price'] + $rs['gas_price'] + $rs['expenses_around'] + $rs['fix_truck'] + $rs['income_driver'] + $rs['truck_period'] + $rs['truck_act']);
            $sum_total_row = ($rs['income'] - $sum_expenses_row);

            $sum_income = $sum_income + $rs['income']; //รายได้รวมทุกเดือน
            $sum_outcome = $sum_outcome + $sum_expenses_row; //รายจ่ายรวมทุกเดือน
        endforeach;


        return array('1' => $sum_income, '2' => $sum_outcome);
    }

    //รายงานค่าใช้จ่ายเกี่ยวกับรถ
    public function actionReport_month_select_car() {
        $searchModel = new \app\models\MapTruckSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report_month_select_car', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMas_report_month_select_car($car_id = null) {
        $Model = new \app\models\MapTruck();
        $MapDriver = new \app\models\MapDriver();
        $DriverModel = new \app\models\Driver();
        $car = $Model->find()->where(['car_id' => $car_id])->one(); //ข้อมูลรถ(มีทะเบียนไรบ้าง)
        $driv = $MapDriver->find()->where(['car_id' => $car_id])->one(); //คันนี้ใครขับ
        $driver = $DriverModel->find()->where(['driver_id' => $driv['driver']])->one(); //ดึงชื่อคนขับ
        return $this->render('mas_report_month_select_car', [
                    'car' => $car,
                    'driver' => $driver,
        ]);
    }

    public function actionLoad_report_month_select_car() {
        $year = \Yii::$app->request->post('year');
        $month = \Yii::$app->request->post('month');
        $car_id = \Yii::$app->request->post('car_id');

        $report = new \app\models\MapTruck();
        $result = $report->get_price($car_id, $year, $month);

        $MapDriver = new \app\models\MapDriver();
        $DriverModel = new \app\models\Driver();
        $car = $report->find()->where(['car_id' => $car_id])->one(); //ข้อมูลรถ(มีทะเบียนไรบ้าง)
        $driv = $MapDriver->find()->where(['car_id' => $car_id])->one(); //คันนี้ใครขับ
        $driver = $DriverModel->find()->where(['driver_id' => $driv['driver']])->one(); //ดึงชื่อคนขับ
        return $this->renderPartial('load_report_month_select_car', [
                    'result' => $result,
                    'car' => $car,
                    'driver' => $driver,
                    'year' => $year,
                    'month' => $month,
        ]);
    }

    //รายงานค่าใช้จ่ายเกี่ยวกับรถต่อรอบวิ่ง
    public function actionReport_month_select_car_round() {
        $searchModel = new \app\models\MapTruckSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('report_month_select_car_round', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMas_report_month_select_car_round($car_id = null) {
        $Model = new \app\models\MapTruck();
        $MapDriver = new \app\models\MapDriver();
        $DriverModel = new \app\models\Driver();
        $car = $Model->find()->where(['car_id' => $car_id])->one(); //ข้อมูลรถ(มีทะเบียนไรบ้าง)
        $driv = $MapDriver->find()->where(['car_id' => $car_id])->one(); //คันนี้ใครขับ
        $driver = $DriverModel->find()->where(['driver_id' => $driv['driver']])->one(); //ดึงชื่อคนขับ
        return $this->render('mas_report_month_select_car_round', [
                    'car' => $car,
                    'driver' => $driver,
        ]);
    }

    public function actionLoad_report_month_select_car_round() {
        $year = \Yii::$app->request->post('year');
        $month = \Yii::$app->request->post('month');
        $car_id = \Yii::$app->request->post('car_id');

        $Assign = new \app\models\Assign();
        $result = $Assign->find()->
                where([
                    'car_id' => $car_id,
                    'LEFT(order_date_start,4)' => $year,
                    'SUBSTR(order_date_start,6,2)' => $month
                ])
                ->orderBy('id')
                ->all();
        $report = new \app\models\MapTruck();
        //$result = $report->get_price($car_id, $year, $month);

        $MapDriver = new \app\models\MapDriver();
        $DriverModel = new \app\models\Driver();
        $car = $report->find()->where(['car_id' => $car_id])->one(); //ข้อมูลรถ(มีทะเบียนไรบ้าง)
        $driv = $MapDriver->find()->where(['car_id' => $car_id])->one(); //คันนี้ใครขับ
        $driver = $DriverModel->find()->where(['driver_id' => $driv['driver']])->one(); //ดึงชื่อคนขับ
        return $this->renderPartial('load_report_month_select_car_round', [
                    'result' => $result,
                    'car' => $car,
                    'driver' => $driver,
                    'year' => $year,
                    'month' => $month,
        ]);
    }

    public function actionGet_sub_expense_round() {
        $type = \Yii::$app->request->post('type');
        $assign_id = \Yii::$app->request->post('assign_id');

        $expenses = new \app\models\Assign();
        $outgoing = new \app\models\Outgoings();

        if ($type == 0) {
            $result = $expenses->get_expense_truck_in_assignid($assign_id);
        } else {
            $result = $outgoing->get_expense_in_assignid($assign_id);
        }

        return $this->renderPartial('load_sub_report_car_round', [
                    'result' => $result,
        ]);
    }

    //รายงานรายรับ รายจ่ายประจำเดือน
    public function actionReport_month_all() {
        return $this->render('report_month_all');
    }

    public function actionLoad_report_month_all() {
        $year = \Yii::$app->request->post('year');
        $month = \Yii::$app->request->post('month');
        $report = new Report();
        $result = $report->report_month_all($year, $month);
        return $this->renderPartial('load_report_month_all', [
                    'year' => $year,
                    'month' => $month,
                    'result' => $result,
        ]);
    }

}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


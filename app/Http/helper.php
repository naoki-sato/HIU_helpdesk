<?php

use Carbon\Carbon;

/**
 * 現在の"年度"を取得
 *
 * @return int 
 */
function fiscalYear() {
    $fiscal_year = Carbon::now()->subMonth(3)->format('Y');
    return $fiscal_year;
}


/**
 * 年(Y)を，その年度初(Y-04-01 00:00:00)にフォーマットする
 * 引数がなければ現在の年度で返す
 * @param  int $year (Y) 
 * @return date Y-04-01 00:00:00
 */
function convertBeginningFiscalYear($year = null){

    if(!$year) return Carbon::create(fiscalYear(), 04, 01 ,00, 00, 00);

    return Carbon::create($year, 04, 01 ,00, 00, 00);
}

/**
 * 年(Y)を，その年度末((Y+1)-03-31 23:59:59)にフォーマットする
 * 引数がなければ現在の年度で返す
 * @param  int $year (Y) 
 * @return date (Y+1)-03-31 23:59:59
 */
function convertEndFiscalYear($year = null){

    if(!$year) return Carbon::create(fiscalYear(), 3, 31 ,23, 59, 59)->addYear(1);
    
    return Carbon::create($year, 3, 31 ,23, 59, 59)->addYear(1);
}



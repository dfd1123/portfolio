<?php
namespace App\Classes;

class Flushing
{
    public function test()
    {
        dd('1234');
    }
    public function tradelist()
    {
        //유효기간 지난 거래대기목록 삭제
        return '1234';
    }
    //파일삭제
    public function garbagecolletor()
    {
        //파일 가질수 있는 모든 테이블 확인하여 Flushing진행
    }
}

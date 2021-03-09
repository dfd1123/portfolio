<?php

namespace App\Http\Controllers\Admin;

use Facades\App\Classes\FileRequest;
use Illuminate\Http\Request;
use Auth;
use DB;

class AnalasisController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('req')) {
            $req = $request->req;
            
            switch ($req) {
            //응시인원
            case 'passed':
        
              //차수가 있는경우
              if ($request->filled('batch')) {
                  $params = array();

                  //학과나 전공이 있는경우
                  $andClause ='';
                
                  if ($request->filled('majorcd') || $request->filled('deptcd')) {
                      $andClause = " AND user_id IN   (SELECT user_id	FROM users WHERE deptcd LIKE :cd OR majorcd LIKE :cd1)  ";
                      //$params['cd'] =  $request->filled('majorcd') ? $request->majorcd : $request->deptcd;
                      $params['cd1'] =  $request->filled('majorcd') ? $request->majorcd : $request->deptcd;
                      $params['cd'] =  $request->filled('majorcd') ? $request->majorcd : $request->deptcd;
                  }

                  $params['batch'] = $request->batch;

                  $sql = "SELECT created_at, JSON_LENGTH(JSON_ARRAYAGG(user_id)) AS ppl
                  FROM (
                  SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at, user_id
                  FROM
                    user_cpt
                    WHERE batch_id = :batch ".$andClause."
                  GROUP BY
                    DATE_FORMAT(created_at, '%Y-%m-%d'), user_id) T1
                  GROUP BY T1.created_at ;";
              
                  $this->res['query'] = DB::select($sql, $params);
                  
                  $this->res['state'] =1;
              }

            break;
          
            //학과요청
            case 'dept':
                //차후 subquery로 index조회하게 수정해야함
                $this->res = DB::select("SELECT DISTINCT dept, deptcd
                FROM   users
                GROUP BY dept, deptcd; ");
              $this->res['state'] =1;
            break;

            //학생의 특정분기 총합
            case 'stdt':
              
              if ($request->filled('user_id', 'batch_id')) {
                  $sql ="SELECT TCT.cpt_title,TUC.cpt_id ,JSON_EXTRACT(ucpt_answer, '$[*][*].value') as VALS
                FROM user_cpt TUC LEFT JOIN cpt_template TCT
                ON TUC.cpt_id = TCT.cpt_id
              WHERE user_id = :user_id AND batch_id = :batch_id ;";

                  $params = array();
                
                  $params['user_id'] = $request->user_id;
                  $params['batch_id'] = $request->batch_id;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;

            //차수간 전체역량(학생전체)
            case 'cmpd_batch':
              if ($request->filled('batch_id')) {
                  $sql ="SELECT TCT.cpt_title,TUC.cpt_id ,JSON_EXTRACT(ucpt_answer, '$[*][*].value') as VALS
                FROM user_cpt TUC LEFT JOIN cpt_template TCT
                ON TUC.cpt_id = TCT.cpt_id
              WHERE batch_id = :batch_id ;";

                  $params = array();
                
                  $params['batch_id'] = $request->batch_id;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;

            //준석 추가
            // 특정학과 원점수 총점
            case 'detp_sum':
              if ($request->filled('deptcd')) {
                  $sql = "SELECT TU.dept, TU.deptcd, SUM(sum_array_cells(JSON_EXTRACT( ucpt_answer, '$[*][*].value' ))) AS ppl
              FROM
                user_cpt TUC
              JOIN users TU ON
                TUC.user_id = TU.user_id
              WHERE deptcd = :deptcd
              GROUP BY
                TU.dept,
                TU.deptcd
              ORDER BY
                ppl DESC";
                  $params = array();
                  
                  $params['deptcd'] = $request->deptcd;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;

            //특정전공 원점수 총점
            case 'major_sum':
              if ($request->filled('majorcd')) {
                  $sql = "SELECT TU.major, TU.majorcd, SUM(sum_array_cells(JSON_EXTRACT( ucpt_answer, '$[*][*].value' ))) AS ppl
              FROM
                user_cpt TUC
              JOIN users TU ON
                TUC.user_id = TU.user_id
              WHERE TU.majorcd = :majorcd
              GROUP BY
                TU.major, TU.majorcd
              ORDER BY
                ppl DESC";
                  $params = array();
                    
                  $params['majorcd'] = $request->majorcd;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;
            //전공별 백분위(계산X, 데이터만)
            case 'dept_perc_rank':
              if ($request->filled('deptcd')) {
                  $sql = "SELECT dept, deptcd, VALS, RANK,
                (
                  SELECT
                    COUNT(deptcd)
                  FROM
                    (
                      SELECT
                        TU.dept, TU.deptcd
                      FROM
                        user_cpt TUC
                      JOIN users TU ON
                        TUC.user_id = TU.user_id
                      GROUP BY
                        TU.dept,
                        TU.deptcd
                    ) batch_dept
                ) AS COUNT
              FROM
                (
                  SELECT
                    Sub1.dept, Sub1.deptcd, Sub1.VALS, @rank := @rank + 1 AS RANK
                  FROM
                    (
                      SELECT
                        TU.dept, TU.deptcd, SUM(sum_array_cells(JSON_EXTRACT( ucpt_answer, '$[*][*].value' ))) AS VALS
                      FROM
                        user_cpt TUC
                      JOIN users TU ON
                        TUC.user_id = TU.user_id
                      GROUP BY
                        TU.dept,
                        TU.deptcd
                      ORDER BY
                        VALS DESC
                    ) Sub1,
                    (
                      SELECT
                        @rank := 0
                    ) b
                ) percent
              WHERE deptcd = :deptcd";
                
                  $params = array();
                    
                  $params['deptcd'] = $request->deptcd;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;

            //학과별 백분위(계산X, 데이터만)
            case 'major_perc_rank':
              if ($request->filled('majorcd')) {
                  $sql = "SELECT
                major,
                majorcd,
                VALS,
                RANK,
                (
                  SELECT
                    COUNT(majorcd)
                  FROM
                    (
                      SELECT
                        TU.major,
                        TU.majorcd
                      FROM
                        user_cpt TUC
                      JOIN users TU ON
                        TUC.user_id = TU.user_id
                      GROUP BY
                        TU.major,
                        TU.majorcd
                    ) batch_major
                ) AS COUNT
              FROM
                (
                  SELECT
                    Sub1.major,
                    Sub1.majorcd,
                    Sub1.VALS,
                    @rank := @rank + 1 AS RANK
                  FROM
                    (
                      SELECT
                        TU.major,
                        TU.majorcd,
                        SUM(sum_array_cells(JSON_EXTRACT( ucpt_answer, '$[*][*].value' ))) AS VALS
                      FROM
                        user_cpt TUC
                      JOIN users TU ON
                        TUC.user_id = TU.user_id
                      GROUP BY
                        TU.major,
                        TU.majorcd
                      ORDER BY
                        VALS DESC
                    ) Sub1,
                    (
                      SELECT
                        @rank := 0
                    ) b
                ) percent
              WHERE majorcd = :majorcd";
                
                  $params = array();
                    
                  $params['majorcd'] = $request->majorcd;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;

            //유저별 백분위(계산 X, 데이터만)
            case 'stdt_perc_rank':
              if ($request->filled('user_id')) {
                  $sql = "SELECT
                user_id, name, user_no, VALS, RANK,
                (
                  SELECT
                    COUNT(user_id)
                  FROM
                    (
                      SELECT
                        user_id, COUNT(user_id)
                      FROM
                        user_cpt
                      GROUP BY
                        user_id
                    ) batch_user
                ) AS COUNT
              FROM
                (
                  SELECT
                    Sub1.name, Sub1.VALS, Sub1.user_id, Sub1.user_no, @rank := @rank + 1 AS RANK
                  FROM
                    (
                      SELECT
                        TU.name, TU.user_id, TU.user_no, SUM(sum_array_cells(JSON_EXTRACT( ucpt_answer, '$[*][*].value' ))) AS VALS
                      FROM
                        user_cpt TUC
                      JOIN users TU ON
                        TUC.user_id = TU.user_id
                      GROUP BY
                        TU.user_id
                      ORDER BY
                        VALS DESC
                    ) Sub1,
                    (
                      SELECT
                        @rank := 0
                    ) b
                ) percent
              WHERE
                user_id = :user_id";
                  $params = array();
                
                  $params['user_id'] = $request->user_id;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;

            //학과별 백분위(계산X, 데이터만)
            case 'coll_perc_rank':
              if ($request->filled('collcd')) {
                  $sql = "SELECT
                coll,
                collcd,
                VALS,
                RANK,
                (
                  SELECT
                    COUNT(collcd)
                  FROM
                    (
                      SELECT
                        TU.coll,
                        TU.collcd
                      FROM
                        user_cpt TUC
                      JOIN users TU ON
                        TUC.user_id = TU.user_id
                      GROUP BY
                        TU.coll,
                        TU.collcd
                    ) batch_coll
                ) AS COUNT
              FROM
                (
                  SELECT
                    Sub1.coll,
                    Sub1.collcd,
                    Sub1.VALS,
                    @rank := @rank + 1 AS RANK
                  FROM
                    (
                      SELECT
                        TU.coll,
                        TU.collcd,
                        SUM(sum_array_cells(JSON_EXTRACT( ucpt_answer, '$[*][*].value' ))) AS VALS
                      FROM
                        user_cpt TUC
                      JOIN users TU ON
                        TUC.user_id = TU.user_id
                      GROUP BY
                        TU.coll,
                        TU.collcd
                      ORDER BY
                        VALS DESC
                    ) Sub1,
                    (
                      SELECT
                        @rank := 0
                    ) b
                ) percent
              WHERE collcd = :collcd";
                
                  $params = array();
                    
                  $params['collcd'] = $request->collcd;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
            break;

            //KJS추가 2020-02-06
            case 'descdeptcd':

              //단과별 총점 내림차순
              if ($request->filled('deptcd', 'batch')) {
                  $sql = "SELECT  TU.name, TU.user_id	, SUM(sum_array_cells(JSON_EXTRACT(ucpt_answer, '$[*][*].value'))) AS VALS
              FROM
                user_cpt TUC LEFT JOIN users TU 
                ON TUC.user_id = TU.user_id
                WHERE TUC.user_id IN (SELECT user_id FROM users WHERE deptcd LIKE :deptcd)
                AND batch_id = :batch
              GROUP BY TUC.user_id
              ORDER BY VALS DESC
              LIMIT :offset ,20;";

                  $offset = 0;

                  if ($request->filled('offset') > 0) {
                      $offset = $request->offset;
                  }


                  $params = array();
                
                  $params['deptcd'] = $request->deptcd;
                  $params['batch'] = $request->batch;
                  $params['offset'] = $request->offset;


                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
          break;

              case 'desccollcd':

                //단과대학별 총점 내림차순
                if ($request->filled('collcd', 'batch')) {
                    $sql = "SELECT  TU.name, TU.user_id	, SUM(sum_array_cells(JSON_EXTRACT(ucpt_answer, '$[*][*].value'))) AS VALS
                FROM
                  user_cpt TUC LEFT JOIN users TU 
                  ON TUC.user_id = TU.user_id
                  WHERE TUC.user_id IN (SELECT user_id FROM users WHERE collcd LIKE :collcd)
                  AND batch_id = :batch
                GROUP BY TUC.user_id
                ORDER BY VALS DESC
                LIMIT :offset ,20;";

                    $offset = 0;

                    if ($request->filled('offset') > 0) {
                        $offset = $request->offset;
                    }


                    $params = array();
                  
                    $params['collcd'] = $request->collcd;
                    $params['batch'] = $request->batch;
                    $params['offset'] = $request->offset;


                    $this->res['query'] = DB::select($sql, $params);
                    $this->res['state'] = 1;
                }
            break;


            //전공별 총점 내림차순
            //majorcd
            
            //KJS추가 2020-02-06
            case 'descmajorcd':

              //단과별 총점 내림차순
              if ($request->filled('majorcd', 'batch')) {
                  $sql = "SELECT
                TU.name, TU.user_id	, SUM(sum_array_cells(JSON_EXTRACT(ucpt_answer, '$[*][*].value'))) AS VALS
              FROM
                user_cpt TUC LEFT JOIN users TU 
                ON TUC.user_id = TU.user_id
                WHERE TUC.user_id IN (SELECT user_id FROM users WHERE majorcd LIKE :majorcd)
                AND batch_id = :batch
              GROUP BY TUC.user_id
              ORDER BY VALS DESC
              LIMIT :offset ,20;";
              
                  $offset = 0;

                  if ($request->filled('offset') > 0) {
                      $offset = $request->offset;
                  }

                  $params = array();
                
                  $params['majorcd'] = $request->majorcd;
                  $params['batch'] = $request->batch;
                  $params['offset'] = $request->offset;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
          break;
          
            //KJS추가 2020-02-06
            //전공별 특정차수 응시인원
            //majorcd
            case 'numppl_major':

              //단과별 총점 내림차순
              if ($request->filled('majorcd', 'batch')) {
                  $sql = "SELECT created_at, JSON_LENGTH(JSON_ARRAYAGG(user_id)) AS ppl
                FROM (
                SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at, user_id
                FROM
                  user_cpt
                  WHERE batch_id = :batch 
                  AND user_id IN (SELECT user_id FROM users WHERE majorcd LIKE :majorcd )
                GROUP BY
                  DATE_FORMAT(created_at, '%Y-%m-%d'), user_id) T1
                GROUP BY T1.created_at
                ORDER BY T1.created_at ASC
              LIMIT :offset ,20;";
              
                  $offset = 0;

                  if ($request->filled('offset') > 0) {
                      $offset = $request->offset;
                  }

                  $params = array();
                
                  $params['majorcd'] = $request->majorcd;
                  $params['batch'] = $request->batch;
                  $params['offset'] = $request->offset;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
          break;

            //KJS추가 2020-02-06
            //단과별 특정차수 응시인원
            //majorcd
            case 'numppl_dept':

              //단과별 총점 내림차순
              if ($request->filled('deptcd', 'batch')) {
                  $sql = "SELECT created_at, JSON_LENGTH(JSON_ARRAYAGG(user_id)) AS ppl
                FROM (
                SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at, user_id
                FROM
                  user_cpt
                  WHERE batch_id = :batch 
                  AND user_id IN (SELECT user_id FROM users WHERE deptcd LIKE :deptcd )
                GROUP BY
                  DATE_FORMAT(created_at, '%Y-%m-%d'), user_id) T1
                GROUP BY T1.created_at
                ORDER BY T1.created_at ASC
              LIMIT :offset ,20;";
              
                  $offset = 0;

                  if ($request->filled('offset') > 0) {
                      $offset = $request->offset;
                  }

                  $params = array();
                
                  $params['deptcd'] = $request->deptcd;
                  $params['batch'] = $request->batch;
                  $params['offset'] = $request->offset;

                  $this->res['query'] = DB::select($sql, $params);
                  $this->res['state'] = 1;
              }
              break;

              case 'numppl_coll':

                //단과별 총점 내림차순
                if ($request->filled('collcd', 'batch')) {
                    $sql = "SELECT created_at, JSON_LENGTH(JSON_ARRAYAGG(user_id)) AS ppl
                  FROM (
                  SELECT DATE_FORMAT(created_at, '%Y-%m-%d') AS created_at, user_id
                  FROM
                    user_cpt
                    WHERE batch_id = :batch 
                    AND user_id IN (SELECT user_id FROM users WHERE collcd LIKE :collcd )
                  GROUP BY
                    DATE_FORMAT(created_at, '%Y-%m-%d'), user_id) T1
                  GROUP BY T1.created_at
                  ORDER BY T1.created_at ASC
                LIMIT :offset ,20;";
                
                    $offset = 0;
  
                    if ($request->filled('offset') > 0) {
                        $offset = $request->offset;
                    }
  
                    $params = array();
                  
                    $params['collcd'] = $request->collcd;
                    $params['batch'] = $request->batch;
                    $params['offset'] = $request->offset;
  
                    $this->res['query'] = DB::select($sql, $params);
                    $this->res['state'] = 1;
                }
                break;

          //학년별 응시기록 특정차수(일자별로)
          // 학부와 전공은 선택사항(기입해도, 안해도됨)
          case 'stdyear_apl_rec':

            if ($request->filled('year', 'batch')) {
                $params = array();

                $deptcd =null;
                if ($request->filled('deptcd')) {
                    $deptcd = $request->deptcd;
                }
                $majorcd =null;
                if ($request->filled('majorcd')) {
                    $majorcd = $request->majorcd;
                }

                //학부와 전공을 같이 넣으면 안됨

                $andClause ="";
                if ($deptcd !=null) {
                    $andClause = " AND deptcd LIKE :deptcd ";
                    $params['deptcd'] = $request->deptcd;
                }
              
                if ($majorcd !=null) {
                    $andClause = " AND majorcd LIKE :majorcd ";
                    $params['majorcd'] = $request->majorcd;
                }

                $sql = "SELECT user_id , DATE_FORMAT(created_at, '%Y-%m-%d')
            FROM user_cpt
            WHERE batch_id = :batch
              AND user_id IN (
                SELECT user_id
              FROM users
              WHERE stdyear = :stdyear ".$andClause." )
            GROUP BY user_id, DATE_FORMAT(created_at, '%Y-%m-%d');";
              
                $params['batch'] = $request->batch;
                $params['stdyear'] = $request->year;

                $this->res['query'] = DB::select($sql, $params);
                $this->res['state'] = 1;
            }
            break;
            
          // 차수 별 총 역량 별 총 진단인원
          case 'type_total_per_batch':
            $this->res['query'] = DB::select("SELECT
                ucpts.cpt_id,
                cpt.cpt_order,
                cpt.cpt_title,
                ucpts.cpt_total
            FROM
            (
                SELECT
                    cpt_id,
                    COUNT(cpt_id) AS cpt_total 
                FROM user_cpt
                WHERE 1 = 1
                    AND batch_id = :batch_id
                GROUP BY cpt_id
            ) AS ucpts
            LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpts.cpt_id
            ORDER BY cpt.cpt_order ASC
            ", [
              'batch_id' => $request->batch_id
            ]);
            
            $this->res['state'] = 1;
            break;

          // 차수 별 총 역량 별 총점 평균
          case 'type_avg_per_batch':
            $this->res['query'] = DB::select("SELECT
                ucpt.cpt_id,
                cpt.cpt_order,
                cpt.cpt_title,
                AVG(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS ucpt_avg
            FROM user_cpt ucpt
            LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
            WHERE 1 = 1
                AND ucpt.batch_id = :batch_id
            GROUP BY ucpt.cpt_id
            ORDER BY cpt.cpt_order ASC
            ", [
              'batch_id' => $request->batch_id
            ]);
            
            $this->res['state'] = 1;
            break;
            
          // 차수 별 전체 총점 평균
          case 'avg_per_batch':
            $this->res['query'] = DB::select("SELECT
                AVG(ucpts.ucpt_avg) AS ucpt_avg
            FROM
            (
                SELECT
                    ucpt.cpt_id,
                    cpt.cpt_title,
                    SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS ucpt_avg
                FROM user_cpt ucpt
                LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
                WHERE 1 = 1
                    AND ucpt.batch_id = :batch_id
                GROUP BY ucpt.cpt_id
            ) AS ucpts
            ", [
              'batch_id' => $request->batch_id
            ]);
            
            $this->res['state'] = 1;
            break;

          // 차수 별 역량 별 전공 별 총점
          case 'major_total_per_batch':
            $this->res['query'] = DB::select("SELECT
                ucpt.cpt_id,
                cpt.cpt_title,
                SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS ucpt_sum
            FROM user_cpt ucpt
            JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
            JOIN users u ON u.user_id = ucpt.user_id
            WHERE 1 = 1
                AND ucpt.batch_id = :batch_id
                AND u.majorcd = :majorcd
            GROUP BY ucpt.cpt_id
            ", [
              'batch_id' => $request->batch_id,
              'majorcd' => $request->majorcd
            ]);
            
            $this->res['state'] = 1;
            break;

          
          // 차수 별 역량 별 학과 별 총점
          case 'dept_total_per_batch':
            $this->res['query'] = DB::select("SELECT
                ucpt.cpt_id,
                cpt.cpt_title,
                SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS ucpt_sum
            FROM user_cpt ucpt
            JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
            JOIN users u ON u.user_id = ucpt.user_id
            WHERE 1 = 1
                AND ucpt.batch_id = :batch_id
                AND u.deptcd = :deptcd
            GROUP BY ucpt.cpt_id
            ", [
              'batch_id' => $request->batch_id,
              'deptcd' => $request->deptcd
            ]);
            
            $this->res['state'] = 1;
            break;

          // 차수 별 역량 별 단과대학 별 총점
          case 'coll_total_per_batch':
            $this->res['query'] = DB::select("SELECT
                ucpt.cpt_id,
                cpt.cpt_title,
                SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS ucpt_sum
            FROM user_cpt ucpt
            JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
            JOIN users u ON u.user_id = ucpt.user_id
            WHERE 1 = 1
                AND ucpt.batch_id = :batch_id
                AND u.collcd = :collcd
            GROUP BY ucpt.cpt_id
            ", [
              'batch_id' => $request->batch_id,
              'collcd' => $request->collcd
            ]);
            
            $this->res['state'] = 1;
            break;

          // 차수 별 역량 별 학과 별 평균
          case 'avg_per_batch':
            $this->res['query'] = DB::select("SELECT
                AVG(ucpts.ucpt_avg) AS ucpt_avg
            FROM
            (
                SELECT
                    ucpt.cpt_id,
                    cpt.cpt_title,
                    SUM(sum_array_cells(JSON_EXTRACT(ucpt.ucpt_answer, '$[*][*].value' ))) AS ucpt_avg
                FROM user_cpt ucpt
                LEFT JOIN cpt_template cpt ON cpt.cpt_id = ucpt.cpt_id
                WHERE 1 = 1
                    AND ucpt.batch_id = :batch_id
                GROUP BY ucpt.cpt_id
            ) AS ucpts
            ", [
              'batch_id' => $request->batch_id
            ]);
            
            $this->res['state'] = 1;
            break;
          }
        }
        return response()->json($this->res);
    }
    public function show(Request $request, $id)
    {
        /*
        DB::select("
        SELECT
            user_id,
            user_no,
            gbn,
            sta,
            name,
            deptcd,
            dept,
            majorcd,
            major,
            stdyear,
            created_at,
            updated_at
        FROM users
        WHERE 1 = 1
            AND user_id = COALESCE(:user_id, user_id)
            AND user_no = COALESCE(:user_no, user_no)
        ORDER BY user_id DESC
        LIMIT :limit OFFSET :offset
        ", [
            'user_id' => data_get($params, 'user_id'),
            'user_no' => data_get($params, 'user_no'),
            'offset' => data_get($params, 'offset') ?? 0,
            'limit' => data_get($params, 'limit') ?? PHP_INT_MAX
        ]);
        */
        return response()->json([]);
    }
}

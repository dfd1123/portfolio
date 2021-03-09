@extends('admin.layouts.app')

@section('content')

<div class="container-fluid">
    <h1 class="mt-4">회사 정보 수정</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{route('admin.settings')}}">설정</a></li>
        <li class="breadcrumb-item active">회사정보 수정</li>
    </ol>
    <div class="card mb-4">
        <div class="card-body">
            <form method="post" action="{{route('admin.settings.update', $company->company_name)}}">
              @csrf
              {{ method_field('PUT') }}
              <div class="form-group">
                <label for="company_name">회사명</label>
                <input type="text" id="company_name" name="company_name" value="{{$company->company_name}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="ceo_name">CEO이름</label>
                <input type="text" id="ceo_name" name="ceo_name" value="{{$company->ceo_name}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="tel">회사 전화번호</label>
                <input type="text" id="tel" name="tel" value="{{$company->tel}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="email">회사 이메일</label>
                <input type="text" id="email" name="email" value="{{$company->email}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="fax">회사 FAX번호</label>
                <input type="text" id="fax" name="fax" value="{{$company->fax}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="cyber_sell_license">통신판매업자번호</label>
                <input type="text" id="cyber_sell_license" name="cyber_sell_license" value="{{$company->cyber_sell_license}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="buisness_number">사업자번호</label>
                <input type="text" id="buisness_number" name="buisness_number" value="{{$company->buisness_number}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="addr1">주소</label>
                <input type="text" id="addr1" name="addr1" value="{{$company->addr1}}"  class="form-control">
              </div>
              <div class="form-group">
                <label for="addr2">상세주소</label>
                <input type="text" id="addr2" name="addr2" value="{{$company->addr2}}"  class="form-control">
              </div>
              <div>
                <button type="submit" class="btn btn-primary btn-lg btn-block">수정</button>
              </div>
            </form>
        </div>
    </div>
    <div style="height: 100vh;"></div>
    <div class="card mb-4"><div class="card-body">When scrolling, the navigation stays at the top of the page. This is the end of the static navigation demo.</div></div>
</div>

@endsection

@section('script')



@endsection
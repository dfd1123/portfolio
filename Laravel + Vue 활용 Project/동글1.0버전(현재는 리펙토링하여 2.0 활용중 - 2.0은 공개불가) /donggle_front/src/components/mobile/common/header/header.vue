<template>
  <!-- header -->
  <header id="dg-hd">
    <!-- 모바일 하단 내비 -->
    <nav class="dg-mo_navi clear_both">
      <a href="#" class="nav_link nav_link_home">home</a>
      <a href="#" class="nav_link nav_link_cate">category</a>
      <!--
          클릭시 .dg-category에 .active
          메뉴클릭시 .dg-category_main > a 에 .active
          서브메뉴 클릭시 .dg-category_sub ._submenu 에 .active
      -->
      <a href="#" class="nav_link nav_link_cart">cart</a>
      <a href="#" class="nav_link nav_link_my">mypage</a>
      <a href="#" class="nav_link nav_link_search">
        <span>search</span>
      </a>
      <!-- gradiant search popup -->
      <div class="dg-gradi_search_wrap">
        <a href="#" class="dg-search_gra_close_btn">close</a>
        <form method="GET">
          <label class="dg_blind">검색</label>
          <input class="dg-search" type="text" placeholder="검색어 미입력시 필터로 연결됩니다." />
          <button class="_search_btn"></button>
        </form>
        <div class="best_search_wrap clear_both">
          <h2>인기검색어</h2>
          <a href="#" class="_best_search">핸드메이드코트</a>
          <a href="#" class="_best_search">롱패딩</a>
        </div>
      </div>
      <!-- gradiant search popup E -->
    </nav>
    <!-- 모바일 하단 내비 E -->
    <div class="dg-hd-con1_wrap">
      <h1 class="dg-hd-logo">donggle</h1>
    </div>

    <!--
      nav_link_search에 .active가 들어오면 .dg-gradi_search_wrap 활성화
    -->

    <!-- main top popup -->
    <div class="dg-main_popup">
      <div class="dg-main_popup_img">지금 동글 가입하면 반값 할인</div>
      <a href="#" class="dg-main_popup-close">팝업닫기</a>
      <div class="dg-main_popup_all"></div>
    </div>
    <!-- main top popup E -->
    <!-- category -->
    <ul class="dg-category">
      <h2>카테고리</h2>
      <div class="clear_both over-y_scroll">
        <li class="dg-category_main dg-category_main01">
          <a href="#" class="active">아우터</a>
          <ul class="dg-category_sub dg-category_sub01">
            <h2 class="dg-category_sub_title">아우터</h2>
            <li>
              <a href="#" class="_submenu _submenu01">자켓</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">가디건</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">점퍼</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">야상</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">베스트</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">코트</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">패딩</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main02">
          <a href="#">상의</a>
          <ul class="dg-category_sub dg-category_sub02">
            <h2 class="dg-category_sub_title">상의</h2>
            <li>
              <a href="#" class="_submenu _submenu01">티셔츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">니트 / 스웨터</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">셔츠/남방</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">맨투맨</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">후드</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">블라우스</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">민소매 / 나시</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main03">
          <a href="#">팬츠</a>
          <ul class="dg-category_sub dg-category_sub03">
            <h2 class="dg-category_sub_title">팬츠</h2>
            <li>
              <a href="#" class="_submenu _submenu01">일자바지</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">슬렉스팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">반바지</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">와이드팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">스키니팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">부츠컷팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">조거팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu08">치마바지</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu09">멜빵바지</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu10">크롭팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu11">배기팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu12">레깅스</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main04">
          <a href="#">스커트</a>
          <ul class="dg-category_sub dg-category_sub04">
            <h2 class="dg-category_sub_title">스커트</h2>
            <li>
              <a href="#" class="_submenu _submenu01">롱스커트</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">미디스커트</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">미니스커트</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main05">
          <a href="#">원피스/세트</a>
          <ul class="dg-category_sub dg-category_sub05">
            <h2 class="dg-category_sub_title">원피스/세트</h2>
            <li>
              <a href="#" class="_submenu _submenu01">미니원피스</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">미디원피스</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">롱원피스</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">투피스 / 세트</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">점프수트</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">시밀러룩</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main06">
          <a href="#">신발</a>
          <ul class="dg-category_sub dg-category_sub06">
            <h2 class="dg-category_sub_title">신발</h2>
            <li>
              <a href="#" class="_submenu _submenu01">힐</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">단화</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">부츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">워커</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">운동화</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">샌들</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">슬리퍼 / 쪼리</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu08">웨딩슈즈</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main07">
          <a href="#">가방</a>
          <ul class="dg-category_sub dg-category_sub07">
            <h2 class="dg-category_sub_title">가방</h2>
            <li>
              <a href="#" class="_submenu _submenu01">숄더백</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">크로스백</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">토트백</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">클러치</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">미니백</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">백팩</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">파우치</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu08">지갑</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu09">에코백 / 캔버스</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main08">
          <a href="#">악세사리</a>
          <ul class="dg-category_sub dg-category_sub08">
            <h2 class="dg-category_sub_title">악세사리</h2>
            <li>
              <a href="#" class="_submenu _submenu01">귀걸이</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">목걸이</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">반지</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">팔찌</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">발찌</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">헤어 악세사리</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">커플 악세사리</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main09">
          <a href="#">패션소품</a>
          <ul class="dg-category_sub dg-category_sub09">
            <h2 class="dg-category_sub_title">패션소품</h2>
            <li>
              <a href="#" class="_submenu _submenu01">휴대폰 케이스</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">양말</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">모자 / 장갑</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">타이즈 / 스타킹</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">스카프 / 머플러</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">시켸</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">벨트</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main10">
          <a href="#">비치웨어</a>
          <ul class="dg-category_sub dg-category_sub10">
            <h2 class="dg-category_sub_title">비치웨어</h2>
            <li>
              <a href="#" class="_submenu _submenu01">비키니</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">모노키니</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">래쉬가드</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">비치원피스</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">비치상의</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">비치팬츠</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">커플웨어</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu08">빅사이즈</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main11">
          <a href="#">란제리/파자마</a>
          <ul class="dg-category_sub dg-category_sub11">
            <h2 class="dg-category_sub_title">란제리/파자마</h2>
            <li>
              <a href="#" class="_submenu _submenu01">파자마 / 홈웨어</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">브라</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">팬티</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">속옷 세트</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu05">슬립</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu06">속바지</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu07">속옷소품</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main12">
          <a href="#">트레이닝</a>
          <ul class="dg-category_sub dg-category_sub12">
            <h2 class="dg-category_sub_title">트레이닝</h2>
            <li>
              <a href="#" class="_submenu _submenu01">트레이닝 상의</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">트레이닝 하의</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">트레이닝 세트</a>
            </li>
          </ul>
        </li>
        <li class="dg-category_main dg-category_main13">
          <a href="#">빅사이즈</a>
          <ul class="dg-category_sub dg-category_sub13">
            <h2 class="dg-category_sub_title">빅사이즈</h2>
            <li>
              <a href="#" class="_submenu _submenu01">아우터</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu02">상의</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu03">하의</a>
            </li>
            <li>
              <a href="#" class="_submenu _submenu04">원피스</a>
            </li>
          </ul>
        </li>
      </div>
    </ul>
    <!-- category E -->

    <!-- search -->
    <div class="dg-black_bar_search_wrap">
      <form method="GET">
        <label class="dg_blind">검색</label>
        <input class="dg-black_bar-search" type="text" placeholder="검색어 미입력시 필터로 연결됩니다." />
        <div class="dg-black_bar-search-bar"></div>
        <button class="dg-black_bar-search_btn"></button>
      </form>
    </div>
    <!-- search E -->

    <!-- main gnb 스크롤시 탑에 고정됨(.scroll) -->
    <div class="dg-main_category_wrap">
      <div class="dg-main_category">
        <div class="dg-main_category_link">
          <a href="#" class="dg-main_category_a">홈</a>
          <a href="#" class="dg-main_category_a">인기상품(BEST)</a>
          <a href="#" class="dg-main_category_a">주간신상(Weekly new)</a>
          <a href="#" class="dg-main_category_a">국내자체제작</a>
        </div>
      </div>
    </div>
    <!-- main gnb E -->

    <!-- 위로가기버튼 -->
    <div class="_go_top">
      <button>위로가기</button>
    </div>
    <!-- 위로가기버튼 E -->
  </header>
  <!-- header E -->
</template>

<script>
export default {};
</script>

<style lang="scss" scoped>
</style>

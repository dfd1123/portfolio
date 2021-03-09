<template>
  <!-- side-view -->
  <section class="side-view">
    <div class="side-container">
      <div class="side-contents">
        <div id="sideAjaxContents">
          <!-- ajax data -->
          <h3 class="side-title">
            설비등록
          </h3>
          <!-- 정보 입력 영역 -->
          <div class="side-content-cell">
            <fieldset>
              <legend>정보 입력 폼</legend>
              <div class="form-group">
                <div class="form-group-list">
                  <div class="form-group-cell grid-02">
                    <label class="form-title">설비ID</label>
                    <div class="form-input">
                      <input
                        type="text"
                        name=""
                        id=""
                        readonly
                        value="zonemain"
                      >
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">설비명</label>
                    <div class="form-input">
                      <input
                        type="text"
                        name=""
                        id=""
                        value="Bufferpath2"
                      >
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">설비모델ID</label>
                    <div class="form-input">
                      <input
                        type="text"
                        name=""
                        id=""
                        readonly
                        value="Checkstream"
                      >
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">작업구역명</label>
                    <div class="form-input">
                      <div class="full-input-btns">
                        <input
                          type="text"
                          name=""
                          id=""
                          readonly
                          value="nz12341234"
                        >
                        <button
                          type="button"
                          class="btn-01 type-04 squre-round"
                          onclick="layerPopOpen('layerPop1');"
                        >
                          <span>구역조회</span>
                        </button>
                      </div>
                      <div
                        id="layerPop1"
                        class="layer-pop layer-pop-parents"
                      >
                        <h4 class="layer-pop-title">
                          구역조회
                        </h4>
                        <div class="list-search-area">
                          <fieldset>
                            <legend>구역조회 검색 폼</legend>

                            <div class="">
                              <ul class="grid-layout">
                                <li class="row-1">
                                  <div class="title-input-cell">
                                    <em>작업구역명 조회</em>
                                    <div class="search-input">
                                      <input
                                        type="text"
                                        name=""
                                        id=""
                                      >
                                      <button type="button">
                                        검색
                                      </button>
                                    </div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                          </fieldset>
                        </div>

                        <!-- 전문가조회 검색 결과 영역 -->
                        <div
                          class="overflow-y-scroll"
                          style="max-height:209px"
                        >
                          <table class="list">
                            <caption>리스트 테이블 표</caption>
                            <colgroup>
                              <col style="width:60px;">
                              <col style="width:auto;">
                              <col style="width:auto;">
                              <col style="width:auto;">
                            </colgroup>
                            <thead>
                              <tr>
                                <th scope="col">
                                  <p class="check-type01 single">
                                    <input
                                      type="checkbox"
                                      id="c0201"
                                      name="c0201"
                                    ><label for="c0201"><span></span></label>
                                  </p>
                                </th>
                                <th scope="col">
                                  작업구역ID
                                </th>
                                <th scope="col">
                                  구역명
                                </th>
                                <th scope="col">
                                  상위작업영역ID
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <p class="check-type01 single">
                                    <input
                                      type="checkbox"
                                      id="c0202"
                                      name="c0201"
                                    ><label for="c0202"><span></span></label>
                                  </p>
                                </td>
                                <td>001209</td>
                                <td>제3 메인구역</td>
                                <td>za201920959</td>
                              </tr>
                              <tr>
                                <td>
                                  <p class="check-type01 single">
                                    <input
                                      type="checkbox"
                                      id="c0203"
                                      name="c0201"
                                    ><label for="c0203"><span></span></label>
                                  </p>
                                </td>
                                <td>001209</td>
                                <td>제3 메인구역</td>
                                <td>za201920959</td>
                              </tr>
                              <tr>
                                <td>
                                  <p class="check-type01 single">
                                    <input
                                      type="checkbox"
                                      id="c0204"
                                      name="c0201"
                                    ><label for="c0204"><span></span></label>
                                  </p>
                                </td>
                                <td>001209</td>
                                <td>제3 메인구역</td>
                                <td>za201920959</td>
                              </tr>
                              <tr>
                                <td>
                                  <p class="check-type01 single">
                                    <input
                                      type="checkbox"
                                      id="c0205"
                                      name="c0201"
                                    ><label for="c0205"><span></span></label>
                                  </p>
                                </td>
                                <td>001209</td>
                                <td>제3 메인구역</td>
                                <td>za201920959</td>
                              </tr>
                              <tr>
                                <td>
                                  <p class="check-type01 single">
                                    <input
                                      type="checkbox"
                                      id="c0206"
                                      name="c0201"
                                    ><label for="c0206"><span></span></label>
                                  </p>
                                </td>
                                <td>001209</td>
                                <td>제3 메인구역</td>
                                <td>za201920959</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <!-- //전문가조회 검색 결과 영역 -->
                        <div class="btn-page-wrap big">
                          <a
                            href="#"
                            class="btn-01 type-03"
                          >삭제</a>
                          <a
                            href="#"
                            class="btn-01 type-01"
                          >수정</a>
                        </div>

                        <button
                          type="button"
                          class="btn-layer-close"
                          onclick="layerPopClose(this);"
                        >
                          레이어팝업 닫기
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">설비 가로, 높이</label>
                    <div class="form-input">
                      <p class="label-input">
                        <input
                          type="number"
                          name="horizontal"
                          id="horizontal"
                          class="auto-width"
                        >
                        <span class="char">M , </span>
                      </p>
                      <p class="label-input">
                        <input
                          type="number"
                          name="vertical"
                          id="vertical"
                          class="auto-width"
                        >
                        <span class="char">M</span>
                      </p>
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">설비유형</label>
                    <div class="form-input">
                      <select
                        disabled
                        style="width:100%;"
                      >
                        <option value="">
                          456.1234
                        </option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">위도, 경도</label>
                    <div class="form-input">
                      <p class="label-input">
                        <input
                          type="number"
                          name="horizontal"
                          id="horizontal"
                          class="auto-width"
                        >
                        <span class="char">, </span>
                      </p>
                      <p class="label-input">
                        <input
                          type="number"
                          name="vertical"
                          id="vertical"
                          class="auto-width"
                        >
                      </p>
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">전문가 배치</label>
                    <div class="form-input">
                      <div class="full-input-btns">
                        <input
                          type="text"
                          name=""
                          id=""
                          value="김길동,홍길동"
                        >
                        <button
                          type="button"
                          class="btn-01 type-04 squre-round"
                        >
                          <span>전문가조회</span>
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">등록자ID</label>
                    <div class="form-input">
                      <input
                        type="text"
                        name=""
                        id=""
                        readonly
                        value="zonemain"
                      >
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">등록일시</label>
                    <div class="form-input">
                      <input
                        type="text"
                        name=""
                        id=""
                        readonly
                        value="2016-10-04 16:39:22"
                      >
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">최근수정자ID</label>
                    <div class="form-input">
                      <input
                        type="text"
                        name=""
                        id=""
                        readonly
                        value="zonemain"
                      >
                    </div>
                  </div>
                  <div class="form-group-cell grid-02">
                    <label class="form-title">최근수정일시</label>
                    <div class="form-input">
                      <input
                        type="text"
                        name=""
                        id=""
                        readonly
                        value="2016-10-04 16:39:22"
                      >
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
          <!-- //정보 입력 영역 -->
          <!-- 설명 입력 영역 -->
          <div class="side-content-cell">
            <h3 class="con-title">
              설명 <span>(2000자 이내)</span>
            </h3>
            <fieldset>
              <legend>설명 입력 폼</legend>
              <div class="input_textarea">
                <textarea></textarea>
              </div>
            </fieldset>
          </div>
          <!-- //설명 입력 영역 -->

          <div class="btn-page-wrap big">
            <p class="btn-pos-right">
              <a
                href="#"
                class="btn-01 type-03"
              >삭제</a>
              <a
                href="#"
                class="btn-01 type-01"
              >수정</a>
            </p>
          </div>

          <!-- //ajax data -->
        </div>

        <button
          type="button"
          class="btn-side-close"
          onclick="sideClose();"
        >
          사이드 닫기
        </button>
      </div>
      <div
        class="side-cover"
        onclick="sideClose();"
      ></div>
    </div>
  </section>
  <!-- //side-view -->
</template>

<script>
  export default {
    
  }
</script>

<style scoped>

</style>

<!-- eslint-disable no-irregular-whitespace -->
<template>
  <div id="app">
    <layout-header
      :title="pageTitle"
      :button-right="saveButtonTitle"
      @button-right-click="saveItemButtonClick"
    >
      <template v-slot:before-button-right>
        <input
          type="button"
          class="rounded-square-btn btn-mild"
          value="목록으로"
          @click="$router.push('/manage-product-list')"
        />
      </template>
      <template v-slot:before-button-right-mobile>
        <input
          type="button"
          class="mobile-btn-theme"
          value="목록으로"
          @click="$router.push('/manage-product-list')"
        />
      </template>
    </layout-header>

    <!-- contents -->
    <div id="admin-container">
      <div class="wrapper">
        <div id="page-manage-prdt-enroll-wrap">
          <div class="grid-col">
            <div class="panel-default-container">
              <div class="panel-default-title">
                <h4 class="in-mainname">상품정보</h4>
              </div>
              <div class="panel-default">
                <div class="form-wrapper">
                  <fieldset class="form-container type-02">
                    <div class="form-align type-category">
                      <label class="form-align-tit">상품 카테고리</label>
                      <div class="form-align-input">
                        <select
                          v-for="(select, index) in categorySelects"
                          :key="index"
                          v-model="select.value"
                          class="form-select"
                          @change="categoryChange(select, index)"
                        >
                          <option :value="null">{{index + 1}}차 카테고리 선택</option>
                          <option
                            v-for="option in select.options"
                            :key="option.id"
                            :value="option.id"
                          >{{_last(option.ca_name.split(' > '))}}</option>
                        </select>
                        <select
                          v-if="categorySelects.length === 0"
                          class="form-select"
                        ></select>
                      </div>
                    </div>

                    <div class="form-align type-prdt-name">
                      <label class="form-align-tit">동글 상품명</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="title"
                          type="text"
                          class="form-input-txt"
                          placeholder="소비자에게 노출되는 상품명을 입력하세요 (5~20자)"
                        />
                      </div>
                    </div>

                    <div class="form-align type-prdt-name">
                      <label class="form-align-tit">사입 상품명</label>
                      <div class="form-align-input">
                        <input
                          v-model="brand"
                          type="text"
                          class="form-input-txt"
                          placeholder="매장에서 사용하는 상품명을 입력하세요 (5~20자)"
                        />
                      </div>
                    </div>

                    <div class="form-align type-prdt-name">
                      <label class="form-align-tit">상품 기본설명</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="simpleIntro"
                          type="text"
                          class="form-input-txt"
                          placeholder="상품에 대한 간단한 설명을 입력해주세요"
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label class="form-align-tit">상품 이미지 등록</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-img-container">
                          <div
                            ref="imageContainer"
                            class="in-group"
                            style="overflow-y: hidden; scroll-behavior: smooth;"
                          >
                            <div
                              v-for="(image, index) in imageList"
                              :key="index"
                              class="img-view-list"
                            >
                              <a
                                href="#"
                                @click.prevent="deleteFile(index)"
                              >
                                <img
                                  :src="image.src"
                                  class="img-view"
                                />
                              </a>
                            </div>
                          </div>
                          <div></div>
                        </div>
                        <input
                          ref="imageInput"
                          type="file"
                          class="none no_event"
                          accept="image/x-png, image/gif, image/jpeg"
                          @change="changeFile"
                        />
                        <input
                          type="button"
                          class="rounded-square-btn btn-outline-navy"
                          value="추가"
                          @click="addFile"
                        />
                      </div>
                    </div>

                    <div class="form-align type-hash">
                      <label class="form-align-tit">상품 해시태그</label>
                      <div class="form-align-input">
                        <div class="in-group">
                          <input
                            type="text"
                            class="form-input-txt"
                            placeholder="태그를 입력하고 Enter 및 추가 버튼을 눌러주세요."
                            maxlength="38"
                            v-model.trim="hashTagInput"
                            @keydown.enter="addHashTag"
                          />
                          <input
                            type="button"
                            class="rounded-square-btn btn-outline-navy"
                            value="추가"
                            @click="addHashTag"
                          />
                        </div>

                        <span class="form-caution">해시태그는 최대 6개까지 추가 가능합니다.</span>

                        <div class="prdt-enroll-hash-container">
                          <h5 class="in-tit">추가한 태그</h5>
                          <div class="in-list-group">
                            <span
                              v-for="(hashTag, index) in hashTagList"
                              :key="index"
                              class="deletable-btn btn-theme"
                            >
                              <span class="in-word">{{hashTag}}</span>
                              <i class="icon-del">
                                <img
                                  src="/images/btn/btn_close_wh.svg"
                                  alt="del btn"
                                  @click="hashTagList.splice(index, 1)"
                                />
                              </i>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align type-group">
                      <div class="in-grid">
                        <div class="in-grid-align">
                          <label class="form-align-tit">단가</label>
                          <div class="form-align-input">
                            <input
                              type="number"
                              class="form-input-txt"
                              placeholder="공급가액을 입력하세요. (VAT 미포함)"
                              v-model.number="taxMny"
                            />
                            <span class="form-caution">
                              부가세가 포함되지 않은 공급가액으로
                              입력해주세요.
                            </span>
                          </div>
                        </div>
                        <div class="in-grid-align">
                          <label class="form-align-tit">시중가</label>
                          <div class="form-align-input">
                            <input
                              type="number"
                              class="form-input-txt"
                              placeholder="공급가액을 입력하세요. (VAT 미포함)"
                              :value="custPrice"
                              readonly="readonly"
                            />
                            <span class="form-caution">
                              시중가는 단가의 두배 가격으로 자동
                              입력됩니다.
                            </span>
                          </div>
                        </div>
                      </div>
                      <div class="in-grid">
                        <div class="prdt-enroll-price-container">
                          <label class="in-label">동글가</label>
                          <div class="in-group">
                            <input
                              type="number"
                              :value="getDongglePrice(taxMny)"
                              placeholder="0"
                              readonly="readonly"
                            />
                            <i>원</i>
                          </div>
                          <span class="in-caution">
                            동글가는 실제 마켓에서 판매될 가격을 말하며,
                            <br />
                            입력하신 단가에 중개수수료 {{this.company.broker_fee}}%와 VAT를
                            포함한 가격입니다. (이벤트시 할인률 적용)
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="form-align type-clean">
                      <label class="form-align-tit">국내자체제작 여부</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-clean-container">
                          <div class="in-list-group">
                            <template>
                              <input
                                v-model="selfType"
                                type="radio"
                                class="chck-box none"
                                id="self_type_n"
                                value="0"
                              />
                              <label
                                class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                                for="self_type_n"
                                @click="originRange=null"
                              >아니요. 국내자체제작은 아닙니다.</label>
                              <input
                                v-model="selfType"
                                type="radio"
                                class="chck-box none"
                                id="self_type_y"
                                value="1"
                              />
                              <label
                                class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                                for="self_type_y"
                                @click="originRange='대한민국'"
                              >예, 국내자체제작 입니다.</label>
                            </template>
                            <span class="form-caution">
                              국산 제품이더라도 자체제작 상품이 아니라면 국내자체제작 상품이 아닙니다.
                              <br />만약 이를 허위로 작성하실 경우 동글 이용에 제한이 생길수도 있습니다.
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align type-madein">
                      <label class="form-align-tit">제조국 (원산지)</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="originRange"
                          type="text"
                          class="form-input-txt"
                          placeholder="제조국(원산지)를 입력하세요."
                        />
                      </div>
                    </div>

                    <div class="form-align type-madein">
                      <label class="form-align-tit">제조사</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="makeCompany"
                          type="text"
                          class="form-input-txt"
                          placeholder="제조사를 입력하세요."
                        />
                      </div>
                    </div>
                    <!--
                    <div
                      class="form-align type-madein"
                      style="display:none;"
                    >
                      <label class="form-align-tit">브랜드</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="brand"
                          type="text"
                          class="form-input-txt"
                          placeholder="브랜드를 입력하세요."
                        />
                      </div>
                    </div>
                    -->
                    <div
                      class="form-align type-madein"
                      style="display:none;"
                    >
                      <label class="form-align-tit">모델</label>
                      <div class="form-align-input">
                        <input
                          v-model.trim="model"
                          type="text"
                          class="form-input-txt"
                          placeholder="모델을 입력하세요."
                        />
                      </div>
                    </div>

                    <div class="form-align">
                      <label class="form-align-tit">배송준비기간</label>
                      <div class="form-align-input">
                        <select
                          v-model="possible_ready_term"
                          class="form-select"
                        >
                          <!--<option value="1">당일 준비 가능</option>-->
                          <option value="2">2일</option>
                          <option value="5">5일</option>
                          <option value="10">10일</option>
                        </select>
                        <span class="form-caution">
                          상품 준비에 평균적으로 걸리는 시일을
                          선택해주세요.
                        </span>
                      </div>
                    </div>

                    <div class="form-align type-clean">
                      <label class="form-align-tit">성별 선택</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-clean-container">
                          <div class="in-list-group">
                            <template v-for="(gen, index) in genderList">
                              <input
                                v-model="gender"
                                v-uniq-id="`gender-${index}`"
                                type="radio"
                                v-uniq-name="`gender-${index}`"
                                class="chck-box none"
                                :key="`input-${index}`"
                                :value="gen.value"
                              />
                              <label
                                v-uniq-for="`gender-${index}`"
                                class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                                :key="`label-${index}`"
                              >{{gen.label}}</label>
                            </template>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align type-clean">
                      <label class="form-align-tit">나이 선택</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-clean-container">
                          <div class="in-list-group">
                            <template v-for="(age, index) in ageList">
                              <input
                                v-model="checkedAgeList"
                                v-uniq-id="`age-${index}`"
                                type="checkbox"
                                class="chck-box none"
                                :key="`input-${index}`"
                                :value="age"
                              />
                              <label
                                v-uniq-for="`age-${index}`"
                                class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                                :key="`label-${index}`"
                              >{{age}}대{{age === '50' ? ' 이상' : ''}}</label>
                            </template>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align">
                      <label class="form-align-tit">색상 선택</label>
                      <div class="form-align-input type-color">
                        <div class="in-group">
                          <input
                            type="text"
                            class="form-input-txt"
                            placeholder="찾으시는 색상이 없는 경우 직접 입력해서 추가해주세요."
                            v-model.trim="colorInput"
                            @keydown.enter="addColor"
                          />
                          <input
                            type="button"
                            class="rounded-square-btn btn-outline-navy"
                            value="추가"
                            @click="addColor"
                          />
                        </div>

                        <div class="prdt-enroll-color-container">
                          <div class="in-list-group">
                            <template v-for="(color, index) in colorList">
                              <input
                                v-uniq-id="`color-${index}`"
                                :key="`input-${index}`"
                                type="checkbox"
                                class="chck-box none"
                                :value="color"
                                v-model="checkedColorList"
                              />
                              <label
                                v-uniq-for="`color-${index}`"
                                :key="`label-${index}`"
                                class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                              >{{color}}</label>
                            </template>
                          </div>
                        </div>

                        <div class="prdt-enroll-color-container">
                          <h5 class="in-tit">선택한 색상</h5>
                          <div class="in-list-group">
                            <span
                              v-for="(checkedColor, index) in checkedColorList"
                              class="deletable-btn btn-mild"
                              :key="checkedColor"
                            >
                              <span class="in-word">{{checkedColor}}</span>
                              <i class="icon-del">
                                <img
                                  src="/images/btn/btn_close_wh.svg"
                                  alt="del btn"
                                  @click="checkedColorList.splice(index, 1)"
                                />
                              </i>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align">
                      <label class="form-align-tit">사이즈 범위 지정</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-size-container">
                          <vue-slider
                            v-model="rangeValue"
                            :marks="rangeMarks"
                            :min="10"
                            :max="150"
                            :dotSize="18"
                            :dotOptions="{style: {'background-color': '#333333'}}"
                            :enable-cross="false"
                          ></vue-slider>
                        </div>
                      </div>
                    </div>

                    <div class="form-align type-choiceop">
                      <label class="form-align-tit">선택옵션 등록</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-create-op-container">
                          <div class="inner">
                            <h5 class="in-tit">
                              <span>
                                옵션항목은 콤마( , ) 로 구분하여 여러개를
                                입력할 수 있습니다. 옷을 예로 들어 [ 옵션1 :
                                사이즈 , 옵션1 항목 : XXL,XL,L,M,S ], [옵션2 :
                                색상 , 옵션2 항목 : 빨강, 파랑, 노랑 ]
                              </span>
                              <b>
                                옵션명과 옵션항목에 따옴표(', ")는 입력할 수
                                없습니다.
                              </b>
                            </h5>
                            <div class="in-list-group">
                              <div
                                v-for="(option, index) in optionList"
                                :key="index"
                                class="in-list"
                              >
                                <div class="in-op-category">
                                  <label>{{`옵션${index + 1}`}}</label>
                                  <input
                                    v-model.trim="option.name"
                                    type="text"
                                    class="form-input-txt"
                                  />
                                </div>
                                <div class="in-op-detail">
                                  <label>{{`옵션${index + 1} 항목`}}</label>
                                  <input
                                    v-model.trim="option.item"
                                    type="text"
                                    class="form-input-txt"
                                  />
                                </div>
                                <button
                                  type="button"
                                  class="option_del_btn"
                                  v-if="index > 1"
                                  @click="OptionRemove(index)"
                                >
                                  <img
                                    data-v-a3a52a74
                                    src="/images/btn/btn_close_wh.svg"
                                    alt="delete button"
                                  />
                                </button>
                              </div>
                            </div>
                            <input
                              type="button"
                              value="옵션항목 추가"
                              class="rounded-square-btn btn-gray"
                              @click="addOptionEntry"
                            />
                            <input
                              type="button"
                              value="옵션목록 생성"
                              class="rounded-square-btn btn-mint"
                              @click="makeOptionListTable"
                            />
                          </div>
                        </div>

                        <div class="prdt-enroll-op-result-container">
                          <div class="in-thead-group">
                            <div class="in-list">
                              <div class="in-th">
                                <span>옵션</span>
                              </div>
                              <div class="in-th">
                                <span>추가금액</span>
                              </div>
                              <div class="in-th">
                                <span>동글가</span>
                              </div>
                              <div class="in-th">
                                <span>품절</span>
                              </div>
                              <div class="in-th">
                                <span>삭제</span>
                              </div>
                            </div>
                          </div>
                          <div class="in-list-group">
                            <div
                              v-for="(optionItem, index) in optionItemList"
                              :key="index"
                              class="in-list"
                            >
                              <div class="in-td td-opname">
                                <span>{{(optionItem.optionPath || []).join(' > ')}}</span>
                              </div>
                              <div class="in-td td-price">
                                <label class="in-label">추가금액</label>
                                <input
                                  v-model.number="optionItem.addAmount"
                                  type="number"
                                  class="form-input-txt"
                                  placeholder="0"
                                  @input="optionItem.price = getDongglePrice(optionItem.addAmount)"
                                />
                              </div>
                              <div class="in-td td-price">
                                <label class="in-label">동글가</label>
                                <input
                                  type="number"
                                  class="form-input-txt"
                                  placeholder="0"
                                  :value="optionItem.price"
                                  readonly="readonly"
                                />
                              </div>
                              <div class="in-td td-sold_out">
                                <span class="show-mobile chck-label">품절</span>
                                <input
                                  type="checkbox"
                                  class="chck-box none"
                                  :id="'sold_out' + index"
                                  :checked="optionItem.sold_out === 1"
                                  @change="OptionSoldOut(index, $event.target.checked)"
                                />
                                <label
                                  :for="'sold_out' + index"
                                  class="check-gradi-circle in-check-circle"
                                ></label>
                              </div>
                              <div class="in-td td-del">
                                <button
                                  type="button"
                                  class="in-delete-btn"
                                  @click="optionItemList.splice(index, 1)"
                                >
                                  <img
                                    src="/images/btn/btn_close_wh.svg"
                                    alt="delete button"
                                  />
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align type-clean">
                      <label class="form-align-tit">세탁 방법</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-clean-container">
                          <div class="in-list-group">
                            <template v-for="(clean, index) in howClean">
                              <input
                                v-model="clean.status"
                                v-uniq-id="`clean-${index}`"
                                type="checkbox"
                                class="chck-box none"
                                :key="`input-${index}`"
                                :value="clean.name"
                              />
                              <label
                                v-uniq-for="`clean-${index}`"
                                class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                                :key="`label-${index}`"
                              >{{clean.name}}</label>
                            </template>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align type-material">
                      <label class="form-align-tit">소재 선택</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-material-container">
                          <div class="in-list-group">
                            <template v-for="(item, index) in material">
                              <input
                                v-model="item.status"
                                v-uniq-id="`material-${index}`"
                                type="checkbox"
                                class="chck-box none"
                                :key="`input-${index}`"
                                :value="item.name"
                              />
                              <label
                                v-uniq-for="`material-${index}`"
                                class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                                :key="`label-${index}`"
                              >{{item.name}}</label>
                            </template>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align">
                      <label class="form-align-tit">기타 상세정보</label>
                      <div class="form-align-input">
                        <div class="prdt-enroll-etc-container">
                          <div class="in-list-group">
                            <div
                              v-for="(detail, index) in etcDetailInfo"
                              :key="index"
                              class="in-list"
                            >
                              <dl>
                                <dt>{{detail.kind}}</dt>
                                <dd>
                                  <template v-for="(item, itemIndex) in detail.option">
                                    <input
                                      v-uniq-id="`fit-${index}-${itemIndex}`"
                                      v-uniq-name="`fit-${index}`"
                                      type="radio"
                                      class="chck-box none"
                                      :key="`input-${itemIndex}`"
                                      :value="item.name"
                                      :checked="item.status"
                                      @input="checkEtcDetail($event, item, detail.option)"
                                    />
                                    <label
                                      v-uniq-for="`fit-${index}-${itemIndex}`"
                                      class="rounded-square-xs-btn btn-outline-gray-light check-navy"
                                      :key="`label-${itemIndex}`"
                                    >{{item.name}}</label>
                                  </template>
                                </dd>
                              </dl>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-align">
                      <label class="form-align-tit">상품 상세설명 (PC 버전)</label>
                      <div
                        id="pc_ck"
                        :class="['form-align-input unreset', {'isNative':isNative}]"
                      >
                        <div class="imageUploadBtn">
                          <button
                            type="button"
                            @click="PcImageUpload($event)"
                          >이미지 업로드</button>
                        </div>
                        <ckeditor
                          :editor="pc.editor"
                          v-model="pc.editorData"
                          :config="pc.editorConfig"
                        ></ckeditor>
                      </div>
                    </div>

                    <div class="form-align">
                      <label class="form-align-tit">
                        상품 상세설명 (Mobile 버전)
                        <span class="form-caution">
                          모바일 버전을 입력하지 않으면 PC버전으로
                          노출됩니다.
                        </span>
                      </label>

                      <div
                        id="mb_ck"
                        :class="['form-align-input unreset', {'isNative':isNative}]"
                      >
                        <div class="imageUploadBtn">
                          <button
                            type="button"
                            @click="MobileImageUpload($event)"
                          >이미지 업로드</button>
                        </div>
                        <ckeditor
                          :editor="mobile.editor"
                          v-model="mobile.editorData"
                          :config="mobile.editorConfig"
                        ></ckeditor>
                      </div>
                    </div>
                  </fieldset>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <layout-footer />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue'
import '@ckeditor/ckeditor5-build-classic/build/translations/ko'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

export default {
  name: 'ManageProductEnroll',
  components: {
    ckeditor: CKEditor.component
  },
  data () {
    return {
      itemId: this._get(this, '$route.params.id', null),
      fetchedItem: null,
      pageTitle: '',
      saveButtonTitle: '',
      caId: null,
      caName: null,
      title: null, // 아이템명
      simpleIntro: null,
      makeCompany: null,
      brand: null,
      model: null,
      imageList: [],
      imageIndexList: [],
      categorySelects: [],
      hashTagList: [],
      hashTagInput: null,
      taxMny: null,
      originRange: null,
      possible_ready_term: 2,
      selfType: 0,
      genderList: [
        {
          label: '전체',
          value: 'man, woman'
        },
        {
          label: '여자',
          value: 'woman'
        },
        {
          label: '남자',
          value: 'man'
        }
      ],
      gender: null,
      ageList: ['10', '20', '30', '40', '50'], // 50 = 50대이상
      checkedAgeList: [],
      colorList: [],
      checkedColorList: [],
      colorInput: null,
      rangeValue: [10, 150],
      rangeMarks: val =>
        val % 10 === 0 && val % 20 !== 0
          ? { label: val === 150 ? '150 이상' : val }
          : false,
      optionList: [
        {
          name: '사이즈',
          item: ''
        },
        {
          name: '색상',
          item: ''
        }
      ],
      optionItemList: [],
      howClean: [
        { name: '손세탁', status: false },
        { name: '드라이(클리닝)', status: false },
        { name: '물세탁', status: false },
        { name: '단독세탁', status: false },
        { name: '울세탁', status: false },
        { name: '표백제 사용금지', status: false },
        { name: '다림질 금지', status: false },
        { name: '세탁기 금지', status: false }
      ],
      material: [
        { name: '면', status: false },
        { name: '폴리에스테르', status: false },
        { name: '나일론', status: false },
        { name: '레이온', status: false },
        { name: '울', status: false },
        { name: '아크릴', status: false },
        { name: '린넨', status: false },
        { name: '스판', status: false },
        { name: '실크', status: false },
        { name: '레더', status: false },
        { name: '캐시미어', status: false },
        { name: '알파카', status: false },
        { name: '텐셀', status: false },
        { name: '모달', status: false },
        { name: '기타', status: false }
      ],
      etcDetailInfo: [
        {
          kind: '핏',
          option: [
            { name: '슬림핏', status: false },
            { name: '보통핏', status: false },
            { name: '박시핏', status: false }
          ]
        },
        {
          kind: ' 촉감',
          option: [
            { name: '부드러움', status: false },
            { name: '보통', status: false },
            { name: '뻣뻣함', status: false }
          ]
        },
        {
          kind: '신축성',
          option: [
            { name: '없음', status: false },
            { name: '보통', status: false },
            { name: '높음', status: false }
          ]
        },
        {
          kind: '비침',
          option: [
            { name: '없음', status: false },
            { name: '보통', status: false },
            { name: '있음', status: false }
          ]
        },
        {
          kind: '두께',
          option: [
            { name: '얇음', status: false },
            { name: '보통', status: false },
            { name: '두꺼움', status: false }
          ]
        }
      ],
      pc: {
        editor: ClassicEditor,
        editorData: '',
        editorConfig: {
          language: 'ko',
          ckfinder: {
            uploadUrl: this.baseUrl() + '/api' + '/Ckfinder/image_upload'
          },
          image: {
            toolbar: [
              'imageTextAlternative',
              '|',
              'imageStyle:alignLeft',
              'imageStyle:full',
              'imageStyle:alignRight'
            ],
            styles: ['full', 'alignLeft', 'alignRight']
          }
        }
      },
      mobile: {
        editor: ClassicEditor,
        editorData: '',
        editorConfig: {
          language: 'ko',
          ckfinder: {
            uploadUrl: this.baseUrl() + '/api' + '/Ckfinder/image_upload'
          },
          image: {
            toolbar: [
              'imageTextAlternative',
              '|',
              'imageStyle:alignLeft',
              'imageStyle:full',
              'imageStyle:alignRight'
            ],
            styles: ['full', 'alignLeft', 'alignRight']
          }
        }
      },
      isNative: false
    }
  },
  beforeCreate () {
    this.$axios
      .get('/api/company/info')
      .then(res => {
        const response = res.data
        if (response.state !== 1) {
          console.log(response.msg)
        } else {
          this.$store.commit('CompanySet', response.query)
        }
      })
      .catch(e => {
        console.log(e)
      })
  },
  async created () {
    window.$EventBus.$on('pc-get-image', this.PcGetImageResult)
    window.$EventBus.$on('mobile-get-image', this.MobileGetImageResult)

    const loginOs = this.GetMobileOperatingSystem()
    if (loginOs === 'Android') {
      if (typeof window.myJs !== 'undefined') {
        this.isNative = true
      }
    } else if (loginOs === 'iOS') {
      if (typeof webkit !== 'undefined') {
        this.isNative = true
      }
    }

    const res = (await this.$axios.get('/api/color')).data
    let colors = res.query.main_colors
    colors = colors.concat(res.query.sub_colors)

    colors.forEach(color => {
      this.colorList.push(color.color_name)
    })

    const inputArray = document.querySelectorAll('#pc_ck .ck-file-dialog-button')
    console.log(inputArray)
    // const inputArray = document.getElementsByName('input')
    inputArray[0].addEventListener('click', this.PrepareUpload, false)

    await this.fetchData()
  },
  beforeDestroy () {
    window.$EventBus.$off('pc-get-image')
    window.$EventBus.$off('mobile-get-image')
  },
  computed: {
    custPrice () {
      return Math.floor(Number(this.taxMny) * 2) // 시중가
    }
  },
  methods: {
    async fetchData () {
      if (this.itemId) {
        this.pageTitle = '상품 정보'
        this.saveButtonTitle = '수정완료'
        await this.searchClick()
      } else {
        this.pageTitle = '상품 등록'
        this.saveButtonTitle = '등록완료'
        await this.searchCategory()
      }
    },
    async searchClick () {
      try {
        this.loading(true)

        const params = {
          store_id: this.store.store_id,
          item_id: this.itemId
        }

        const data = await this.$axios
          .get('/api/store/items/view', {
            params
          })
          .then(this.normalOrError)
          .then(this.resultOrError)

        if (data.items && data.items.length === 0) {
          alert('잘못된 데이터입니다')
          return this.$router.push('/manage-product-list')
        }

        const item = this._head(data.items)
        this.fetchedItem = item

        this.caId = item.ca_id
        const promise1 = this.restoreCategory()

        this.title = item.title
        this.simpleIntro = item.simple_intro

        this.imageList = JSON.parse(item.images).map(x => ({
          type: 'url',
          src: this.storagePath(x),
          data: x
        }))

        this.imageIndexList = JSON.parse(item.images)

        this.hashTagList = (item.hash_tag || '').split(',').map(x => x.trim())

        this.taxMny = Number(item.tax_mny || 0)

        this.originRange = item.orgin_range
        this.makeCompany = item.make_company
        this.brand = item.brand
        this.model = item.model

        this.possible_ready_term = item.possible_ready_term
        this.selfType = item.self_type
        this.gender = item.gender
        this.checkedAgeList = (item.age || '').split(',').map(x => x.trim())
        this.checkedColorList = (item.color || '')
          .split(',')
          .map(x => x.trim())
        this.rangeValue = [Number(item.min_size), Number(item.max_size)]

        this.optionList = (item.option_subject || '')
          .split(',')
          .map((x, index) => ({ name: x.trim(), item: '' }))
        this.optionItemList = (JSON.parse(item.options || '[]') || []).map(
          x => ({
            optionPath: (x.op_name || '')
              .split(',')
              .map(x => x.trim())
              .filter(x => x),
            addAmount: Number(x.op_tax_mny),
            price: this.getDongglePrice(x.op_tax_mny),
            sold_out: x.sold_out
          })
        )

        JSON.parse(item.how_clean).forEach(x => {
          const found = this.howClean.find(y => y.name === x.name)
          if (found) {
            found.status = x.status
          }
        })

        JSON.parse(item.material).forEach(x => {
          const found = this.material.find(y => y.name === x.name)
          if (found) {
            found.status = x.status
          }
        })

        JSON.parse(item.etc_detail_info).forEach(row => {
          const foundRow = this.etcDetailInfo.find(x => x.kind === row.kind)
          if (foundRow) {
            foundRow.option.forEach(option => {
              const found = row.option.find(x => x.name === option.name)
              if (found) {
                option.status = found.status
              }
            })
          }
        })

        this.pc.editorData = item.introduce_pc
        this.mobile.editorData = item.introduce_mobile

        await promise1
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async restoreCategory () {
      try {
        this.loading(true)

        this.categorySelects = []
        await this.searchCategory()

        const categoryPath = this.caId.match(/.{1,2}/g)
        for (const [index, path] of categoryPath.entries()) {
          const current = this._get(this, `categorySelects[${index}]`, null)
          if (!current) {
            break
          }

          const currentId = this._take(categoryPath, index)
            .concat([path])
            .join('')
          if (!current.options.find(x => x.id === currentId)) {
            break
          }

          current.value = currentId

          await this.categoryChange(current, index)
        }
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async saveItemButtonClick () {
      if (this.itemId) {
        await this.update()
      } else {
        await this.create()
      }
    },
    async searchCategory () {
      try {
        this.loading(true)

        const data = await this.$axios
          .get('/api/category/level_search', {})
          .then(this.normalOrError)
          .then(this.resultOrError)

        this.categorySelects.push({
          value: null,
          options: data.next_categorys
        })
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async levelSearchCategory (categoryId) {
      try {
        this.loading(true)

        const data = await this.$axios
          .get('/api/category/level_search', {
            params: {
              id: categoryId
            }
          })
          .then(this.normalOrError)
          .then(this.resultOrError)

        return data
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async categoryChange (select, index) {
      this.categorySelects.length = index + 1
      if (select.value) {
        const result = await this.levelSearchCategory(select.value)
        if (result.next_categorys.length > 0) {
          this.categorySelects.push({
            value: null,
            options: result.next_categorys
          })
        }
      }

      const reverse = this.categorySelects.slice().reverse()

      let found = null
      for (const select of reverse) {
        if (select.value) {
          found = select
          break
        }
      }

      this.caId = found === null ? null : found.value
      this.caName =
          found === null
            ? null
            : found.options.find(x => x.id === found.value).ca_name
    },
    addHashTag () {
      if (this.hashTagInput && this.hashTagList.length < 6) {
        this.hashTagList.push(this.hashTagInput)
        this.hashTagInput = null
      }
    },
    async addColor () {
      if (
        !this.colorInput &&
          this.colorInput === ' ' &&
          this.colorInput === ''
      ) {
        return false
      }
      const color = this.colorInput.toString().replace(' ', '')

      const params = {
        color_name: color,
        kind: 2
      }

      try {
        const res = (await this.$axios.post('/api/color', params)).data

        if (res.state === 1) {
          this.colorList = this._uniq(this.colorList.concat([this.colorInput]))
          this.colorInput = null
        } else if (res.state === 55) {
          alert('이미 존재하는 색상입니다.')
        } else {
          console.log(res.msg)
        }
      } catch (e) {
        console.log(e)
      }
    },
    addOptionEntry () {
      this.optionList.push({
        name: null,
        item: null
      })
    },
    makeOptionListTable () {
      const checkBlank1 = this.optionList.some(
        option => option.name && !option.item
      )
      if (checkBlank1) {
        alert('옵션항목이 빈칸인 옵션명이 있습니다')
        return
      }

      const checkBlank2 = this.optionList.some(
        option => option.item && !option.name
      )
      if (checkBlank2) {
        alert('옵션명이 빈칸인 옵션항목이 있습니다')
        return
      }

      const checkComma = this.optionList.some(
        option =>
          option.name &&
            ["'", '"'].some(
              x =>
                (option.name || '').includes(x) ||
                ["'", '"'].some(x => (option.item || '').includes(x))
            )
      )
      if (checkComma) {
        alert("옵션명과 옵션항목에 따옴표(', \")는 입력할 수 없습니다")
        return
      }

      // Cartesian product of multiple arrays
      const f = (a, b) => [].concat(...a.map(a => b.map(b => [].concat(a, b))))
      const cartesian = (a, b, ...c) => (b ? cartesian(f(a, b), ...c) : a)

      this.optionItemList = cartesian(
        ...this.optionList.map(option =>
          option.item
            .split(',')
            .filter(x => x)
            .map(x => x.trim())
        )
      ).map(x => ({
        optionPath: x,
        addAmount: 0,
        price: 0,
        sold_out: 0
      }))
    },
    checkEtcDetail (e, item, options) {
      options.forEach(x => {
        x.status = false
      })
      item.status = e.target.value === item.name
    },
    async create () {
      try {
        this.loading(true)

        if (!this.checkRequired()) {
          return
        }

        const params = {
          ca_id: this.caId,
          ca_name: this.caName,
          age: this.checkedAgeList
            .map(x => parseInt(x))
            .sort((a, b) => a - b)
            .map(x => String(x))
            .join(','),
          gender: this.gender,
          self_type: this.selfType,
          title: this.title,
          simple_intro: this.simpleIntro,
          possible_ready_term: Number(this.possible_ready_term),
          introduce_pc: this.pc.editorData,
          introduce_mobile: this.mobile.editorData
            ? this.mobile.editorData
            : this.pc.editorData,
          make_company: this.makeCompany,
          orgin_range: this.originRange,
          brand: this.brand,
          model: this.model,
          info_gubun: 'clothing', // 정확한 용도 모름
          min_size: this.rangeValue[0],
          max_size: this.rangeValue[1],
          color: this.checkedColorList.join(','),
          tax_mny: this.taxMny,
          option_subject: this.optionList.map(x => x.name).join(','),
          sc_type: 0,
          hash_tag: this.hashTagList.join(','),
          how_clean: JSON.stringify(this.howClean),
          material: JSON.stringify(this.material),
          etc_detail_info: JSON.stringify(this.etcDetailInfo),
          options: JSON.stringify(
            this.optionItemList.map(x => ({
              op_name: x.optionPath.join(','),
              op_tax_mny: x.addAmount,
              op_notax: 1, // 과세유형 0:비과세 1: 과세 -> 우선 테이블 디폴트값 1로 설정
              sold_out: x.sold_out
            }))
          ),
          images: this.imageList
            .filter(x => x.type === 'file')
            .map(x => x.data)
            .filter(x => x)
        }

        const { formData, headers } = this.formDatas(params)

        const res = await this.$axios
          .post('/api/store/items', formData, { headers })
          .then(this.normalOrError)

        if (res.data.state === 1) {
          alert('상품 등록 완료!')
          this.$router.push('/manage-product-list')
        } else {
          alert(res.msg)
        }
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    async update () {
      try {
        this.loading(true)

        if (!this.checkRequired()) {
          return
        }

        const params = {
          _method: 'put',
          item_id: this.itemId,
          ca_id: this.caId,
          ca_name: this.caName,
          age: this.checkedAgeList
            .map(x => parseInt(x))
            .sort((a, b) => a - b)
            .map(x => String(x))
            .join(','),
          gender: this.gender,
          self_type: this.selfType,
          title: this.title,
          simple_intro: this.simpleIntro,
          possible_ready_term: Number(this.possible_ready_term),
          introduce_pc: this.pc.editorData,
          introduce_mobile: this.mobile.editorData
            ? this.mobile.editorData
            : this.pc.editorData,
          make_company: this.makeCompany,
          orgin_range: this.originRange,
          brand: this.brand,
          model: this.model,
          info_gubun: 'clothing', // 정확한 용도 모름
          min_size: this.rangeValue[0],
          max_size: this.rangeValue[1],
          color: this.checkedColorList.join(','),
          tax_mny: this.taxMny,
          option_subject: this.optionList
            .map(x => x.name)
            .filter(x => x)
            .join(','),
          sc_type: 0,
          hash_tag: this.hashTagList.join(','),
          how_clean: JSON.stringify(this.howClean),
          material: JSON.stringify(this.material),
          etc_detail_info: JSON.stringify(this.etcDetailInfo),
          options: JSON.stringify(
            this.optionItemList.map(x => ({
              op_name: x.optionPath.join(','),
              op_tax_mny: x.addAmount,
              op_notax: 1, // 과세유형 0:비과세 1: 과세 -> 우선 테이블 디폴트값 1로 설정
              sold_out: x.sold_out
            }))
          ),
          images: this.imageList
            .filter(x => x.type === 'file')
            .map(x => x.data)
            .filter(x => x),
          index: this.imageIndexList.filter(x => x)
        }

        const { formData, headers } = this.formDatas(params)

        const res = await this.$axios
          .post('/api/store/items/item_update', formData, { headers })
          .then(this.normalOrError)

        if (res.data.state === 1) {
          alert('상품 수정 완료!')
          await this.searchClick()
        } else {
          alert(res.msg)
        }
      } catch (e) {
        console.log(e)
        alert(e.response ? e.response.data.msg || '에러발생' : e)
      } finally {
        this.loading(false)
      }
    },
    checkRequired () {
      if (
        !this.optionList.map(x => x.name).includes('사이즈') ||
          !this.optionList.map(x => x.name).includes('색상')
      ) {
        alert('사이즈와 색상은 필수옵션입니다.')
        return false
      }

      if (!this.ca_name && !this.caName) {
        alert('카테고리를 선택해주세요.')
        return false
      }

      if (!this.title) {
        alert('동글 상품명을 입력해주세요.')
        return false
      } else {
        if (this.title.length < 5) {
          alert('동글 상품명이 너무 짧습니다.')
          return false
        }
      }

      if (!this.brand) {
        alert('사입 상품명을 입력해주세요.')
        return false
      } else {
        if (this.brand.length < 5) {
          alert('사입 상품명이 너무 짧습니다.')
          return false
        }
      }

      if (!this.simpleIntro) {
        alert('상품 기본설명을 입력 해주세요.')
        return false
      }

      if (this.imageList.length === 0) {
        alert('상품 이미지를 업로드 해주세요.')
        return false
      }

      if (this.hashTagList.length === 0) {
        alert('해시태그들을 추가 해주세요.')
        return false
      }

      if (!this.taxMny) {
        alert('단가를 입력 해주세요.')
        return false
      }

      if (!this.originRange) {
        alert('제조국(원산지)를 입력 해주세요.')
        return false
      }

      if (!this.makeCompany) {
        alert('제조사를 입력 해주세요.')
        return false
      }

      if (this.checkedAgeList.length === 0) {
        alert('상품에 적합한 나이대를 선택 해주세요.')
        return false
      }

      if (this.checkedColorList.length === 0) {
        alert('상품 검색에 활용되기 위한 색상을 선택 해주세요.')
        return false
      }

      if (this.optionItemList.length === 0) {
        alert('사이즈와 색상은 선택 해주세요.')
        return false
      }

      if (this.pc.editorData.length === 0) {
        alert('상품 상세설명을 입력 해주세요.')
        return false
      }

      return true
    },
    addFile () {
      if (this.imageList.length >= 9) {
        alert('이미지는 최대 9개까지 추가 가능합니다')
        return
      }

      this.$refs.imageInput.click()
    },
    OptionSoldOut (index, val) {
      console.log(index, val)
      if (val) {
        this.optionItemList[index].sold_out = 1
      } else {
        this.optionItemList[index].sold_out = 0
      }
    },
    deleteFile (index) {
      if (confirm('해당 이미지를 삭제하시겠습니까?')) {
        const image = this.imageList[index]
        if (image.type === 'url') {
          this.imageIndexList = this.imageIndexList.filter(
            x => x !== image.data
          )
        }
        this.imageList.splice(index, 1)
      }
    },
    OptionRemove (index) {
      this.optionList.splice(index, 1)
      this.makeOptionListTable()
    },
    async changeFile (e) {
      const { file, dataURL } = await this.imagefileChange(e)

      this.imageList.push({
        type: 'file',
        src: dataURL,
        data: file
      })

      e.target.value = ''
      setTimeout(() => {
        this.$refs.imageContainer.scrollLeft =
            this.$refs.imageContainer.scrollWidth +
            this.$refs.imageContainer.offsetWidth
      }, 0)
    },
    async PcImageUpload (e) {
      const loginOs = this.GetMobileOperatingSystem()
      if (loginOs === 'Android') {
        if (typeof window.myJs !== 'undefined') {
          window.myJs.CkCustomImage('pc')
          this.loading(true)
        }
      } else if (loginOs === 'iOS') {
        if (typeof webkit !== 'undefined') {
          window.webkit.messageHandlers.requestAlbum.postMessage('pc')
          this.loading(true)
        }
      }
    },
    async MobileImageUpload (e) {
      const loginOs = this.GetMobileOperatingSystem()
      if (loginOs === 'Android') {
        if (typeof window.myJs !== 'undefined') {
          window.myJs.CkCustomImage('mobile')
          this.loading(true)
        }
      } else if (loginOs === 'iOS') {
        if (typeof webkit !== 'undefined') {
          window.webkit.messageHandlers.requestAlbum.postMessage('mobile')
          this.loading(true)
        }
      }
    },
    PcGetImageResult (res) {
      const imgArr = JSON.parse(res)
      imgArr.forEach(img => {
        this.pc.editorData += '<figure class="image"><img src="' + this.storagePath(img) + '"></figure><p>&nbsp;</p>'
      })

      this.loading(false)
    },
    MobileGetImageResult (res) {
      const imgArr = JSON.parse(res)
      imgArr.forEach(img => {
        this.mobile.editorData += '<figure class="image"><img src="' + this.storagePath(img) + '" ></figure><p>&nbsp;</p>'
      })

      this.loading(false)
    }
  }
}
</script>

<style lang="scss" scoped>
  ::v-deep .unreset {
    @import "node_modules/unreset-css/unreset";

    ol {
      list-style: decimal;
    }

    ul {
      list-style: disc;
    }

    li {
      list-style: inherit;
    }
  }

  ::v-deep {
    .ck-editor__editable_inline {
      min-height: 300px;
    }

    .ck.ck-toolbar.ck-toolbar_grouping > .ck-toolbar__items {
      flex-wrap: wrap;
    }
  }

  .img-view-list {
    display: inline-block;
    margin-right: 5px;
  }

  .img-view {
    height: 200px;
    object-fit: contain;
    border: 1px dotted black;
  }

  @media all and (max-width: 991px) {
    .prdt-enroll-op-result-container .in-list-group .in-list .in-td.td-price {
      width: 40%;
    }
    .prdt-enroll-op-result-container .in-list-group .td-sold_out {
      border: 0px none;
    }
    .prdt-enroll-op-result-container .in-list-group .td-sold_out .chck-label {
      display: inline-block;
      margin-bottom: 5px;
      font-size: 13px;
      color: #abafb3;
    }
    .prdt-enroll-op-result-container
      .in-list-group
      .td-sold_out
      .check-gradi-circle {
      margin-top: 10px;
    }

    #pc_ck .ck-file-dialog-button,
    #mb_ck .ck-file-dialog-button {
      display: none;
    }
  }
</style>
<style>
  .imageUploadBtn {
    display: none;
  }

  @media all and (max-width: 991px) {
    .prdt-enroll-op-result-container .in-list-group .in-list .in-td.td-price {
      width: 40%;
    }
    .prdt-enroll-op-result-container .in-list-group .td-sold_out {
      border: 0px none;
    }
    .prdt-enroll-op-result-container .in-list-group .td-sold_out .chck-label {
      display: inline-block;
      margin-bottom: 5px;
      font-size: 13px;
      color: #abafb3;
    }
    .prdt-enroll-op-result-container
      .in-list-group
      .td-sold_out
      .check-gradi-circle {
      margin-top: 10px;
    }

    #pc_ck.isNative .ck-file-dialog-button,
    #mb_ck.isNative .ck-file-dialog-button {
      display: none;
    }

    #pc_ck,
    #mb_ck {
      position: relative;
    }

    .isNative .imageUploadBtn {
      display: block;
      position: absolute;
      top: -35px;
      right: 0px;
      z-index: 10;
    }

    #mb_ck.isNative .imageUploadBtn {
      top: -59px;
    }

    .isNative .imageUploadBtn button {
      background: #fff;
      display: block;
      font-size: 13px;
      padding: 5px 10px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }

    .vue-slider-mark-label {
      font-size: 11px;
    }
  }
</style>

<template>
  <div class="yg-list">
    <van-nav-bar class="yg-nav-bar" title="商品列表" left-arrow @click-left="onClickLeft"/>
    <div class="list-content">
      <van-search placeholder="请输入搜索关键词" v-model="searchValue" show-action>
        <div slot="action" @click="onSearch">搜索</div>
      </van-search>
      <van-row class="header-icon">
        <van-col span="8">
          <div :class="{'active': filterType === 1 || filterType === 2}" class="filter" @click="filterRadioAllTypeShow">
            {{ filterType === 1 ? '综合' : '排序' }}
            <van-icon name="arrow-down"/>
          </div>
        </van-col>
        <van-col span="8" :class="{'activeOBJ': filterType === 3 || filterType === 4}">
          <div v-if="filterLabel1" class="filter" @click="filterLabelChange1(3)">存库
            <van-icon name="arrow-down"/>
          </div>
          <div v-else class="filter" @click="filterLabelChange1(4)">存库
            <van-icon name="arrow-up"/>
          </div>
        </van-col>
        <van-col span="8" :class="{'activeOBJ': filterType === 5 || filterType === 6}">
          <div v-if="filterLabel2" class="filter" @click="filterLabelChange2(5)">价格
            <van-icon name="arrow-down"/>
          </div>
          <div v-else class="filter" @click="filterLabelChange2(6)">价格
            <van-icon name="arrow-up"/>
          </div>
        </van-col>
        <!--        <van-col span="4"><van-icon name="qr"/></van-col>-->
      </van-row>
      <div v-if="isShowFilter" class="filter-all-type">
        <van-radio-group v-model="filterRadioType" @change="filterRadioTypeChange">
          <van-cell-group>
            <van-cell title="综合" clickable @click="filterRadioType = '1'">
              <van-radio name="1"/>
            </van-cell>
            <van-cell title="排序" clickable @click="filterRadioType = '2'">
              <van-radio name="2"/>
            </van-cell>
          </van-cell-group>
        </van-radio-group>
      </div>
      <!--数据内容-->
      <div class="yg-list-content">
        <van-row>
          <van-col span="12" class="item" v-for="item in goodList" :key="item.goods_id">
            <div @click="goToItem(item.goods_id)">
              <div class="img"><img
                :src="item.file.file_path"
                :alt="item.goods_name">
              </div>
              <div class="title">{{item.goods_name}}</div>
              <div class="price">￥{{item.goods_min_price}}</div>
              <div class="info">销量：{{item.goods_sales}}件</div>
            </div>
          </van-col>
        </van-row>

<!--        <van-pagination-->
<!--          v-model="currentPage"-->
<!--          :items-per-page="15"-->
<!--          :total-items="totalCount"-->
<!--          mode="simple"-->
<!--          class="yg-pagination"-->
<!--          @change="changePage"-->
<!--        />-->
      </div>
    </div>
  </div>
</template>

<script>
import {getGoodsListApi} from '../../api/list'

export default {
  name: "list",
  components: {},
  data() {
    return {
      searchValue: '',
      filterRadioType: '1',
      isShowFilter: false,

      filterType: 1, // 筛选ID

      filterLabel1: false,
      filterLabel2: false,

      goodList: null, // 商品列表
      currentPage: 1,
      totalCount: 0,
    }
  },
  watch: {
    filterType() {
      const self = this;
      if(self.filterType === 1) {
        self.getGoodsList(0, self.searchValue, 'all', 0);
      }
      if(self.filterType === 2) {
        self.getGoodsList(0, self.searchValue, 'sales', 0);
      }
      if(self.filterType === 3) {
        self.getGoodsList(0, self.searchValue, 'sales', 0);
      }
      if(self.filterType === 4) {
        self.getGoodsList(0, self.searchValue, 'sales', 1);
      }
      if(self.filterType === 5) {
        self.getGoodsList(0, self.searchValue, 'price', 0);
      }
      if(self.filterType === 6) {
        self.getGoodsList(0, self.searchValue, 'price', 1);
      }
    }
  },
  methods: {
    /**
     * 返回
     */
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    /**
     * 筛选
     */
    filterChange(type) {
      const self = this;
      self.filterType = type;
    },
    filterLabelChange1(type) {
      const self = this;
      self.filterType = Number(type);
      self.filterLabel1 = !self.filterLabel1;
      self.isShowFilter = false;
    },
    filterLabelChange2(type) {
      const self = this;
      self.filterType = Number(type);
      self.filterLabel2 = !self.filterLabel2;
      self.isShowFilter = false;
    },
    /**
     * 显示筛选
     */
    filterRadioAllTypeShow() {
      const self = this;
      self.isShowFilter = true;
    },
    /**
     * radio改变值触发 排序 综合
     */
    filterRadioTypeChange(name) {
      const self = this;
      self.filterType = Number(name);
      self.isShowFilter = false;
    },
    // 获取商品信息
    getGoodsList(cid, search, type, price) {
      const self = this;
      getGoodsListApi({
        category_id: cid,
        search: search,
        sortType: type,
        sortPrice: price
      }).then(response => {
        self.goodList = response.data.list.data;
        self.totalCount = response.data.list.total;
      }).catch(error => {
        console.log(error);
      })
    },
    // 分页
    changePage() {
      const self = this;
    },
    // 跳转到详细页
    goToItem(id) {
      const self = this;
      self.$router.push({
        path: '/item',
        query: {
          id: id
        }
      })
    },
    // 搜索
    onSearch() {
      const self = this;
      self.getGoodsList('', self.searchValue)
    }
  },
  created() {
    const self = this;
    self.getGoodsList();
  },
  mounted() {

  }
}
</script>

<style lang="less">
.yg-list {
  .list-content {
    .header-icon {
      display: flex;
      text-align: center;
      padding: 0.25rem 0;
      background-color: #fff;

      .van-col {
        align-self: center;
        font-size: 14px;
        color: #333;

        .filter {
          i {
            font-size: 12px;
            top: 0;
          }

          &.active {
            color: @mainColor;
          }
        }

        i {
          font-size: 20px;
          position: relative;
        }

        &.activeOBJ {
          .filter {
            color: @mainColor;
          }
        }
      }
    }

    .filter-all-type {
      .van-radio__icon--checked {
        .van-icon {
          border-color: @mainColor;
          background-color: @mainColor;
        }
      }
    }

    .yg-list-content {
      padding: 0.25rem;
      background-color: #fff;

      .item {
        text-align: left;
        padding-bottom: 0.2rem;

        .img {
          overflow: hidden;
          padding: 0 0.1rem;

          img {
            width: 100%
          }
        }

        .title {
          font-size: 13px;
          color: #333;
          overflow: hidden;
          text-overflow: ellipsis;
          white-space: nowrap;
          padding-right: 0.1rem;
          padding-left: 0.1rem;
        }

        .price {
          font-size: 24px;
          color: #FA4E1B;
          padding-top: 0.1rem;
          padding-bottom: 0.1rem;
          padding-right: 0.1rem;
          padding-left: 0.1rem;
        }

        .info {
          font-size: 10px;
          color: #999;
          padding-right: 0.1rem;
          padding-left: 0.1rem;
          padding-bottom: 0.25rem;
        }
      }
    }
  }
}
</style>

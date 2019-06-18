<template>
  <div class="yg-user-address">
    <van-nav-bar class="yg-nav-bar" title="我的收货地址" left-arrow @click-left="onClickLeft"/>
    <div v-if="hasData" class="user-address-list">
      <van-swipe-cell :right-width="65" class="address-item" v-for="item in addressList" :key="item.address_id">
        <van-cell-group>
          <van-cell>
            <div slot="title">
              <div class="address-title">{{item.name}} {{item.phone}} <span v-html="defalutAddressItem(item.address_id)"></span> </div>
              <div class="address-sub-title">{{item.region.province}}{{item.region.city}}{{item.region.region}}{{item.detail}}</div>
            </div>
            <van-icon slot="right-icon" name="edit" class="custom-icon" @click="editAddress(item.address_id)"/>
          </van-cell>
        </van-cell-group>
        <div slot="right" class="yg-swtch" @click="deleteSwtichRight(item.address_id)">
          <span class="title">删除</span>
        </div>
      </van-swipe-cell>

<!--      <van-swipe-cell :right-width="65" class="address-item">-->
<!--        <van-cell-group>-->
<!--          <van-cell>-->
<!--            <div slot="title">-->
<!--              <div class="address-title">周成 13812880019</div>-->
<!--              <div class="address-sub-title">江苏省淮安市清江浦区xxxxx</div>-->
<!--            </div>-->
<!--            <van-icon slot="right-icon" name="edit" class="custom-icon" />-->
<!--          </van-cell>-->
<!--        </van-cell-group>-->
<!--        <div slot="right" class="yg-swtch" @click="deleteSwtichRight">-->
<!--          <span class="title">删除</span>-->
<!--        </div>-->
<!--      </van-swipe-cell>-->

      <button class="yg-address-list_add van-button van-button--danger van-button--large van-button--square van-address-list__add" @click="goToAddAddress">
        <span class="van-button__text">新增地址</span>
      </button>
    </div>
    <div v-else class="yg-no-data text-center">
      <div class="img">
        <img src="data:image/png;base64,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" alt="没有数据">
      </div>
      <div class="title">
        您还没有收货地址哦！
      </div>
      <div class="btn">
        <van-button size="small" class="no-data-btn" @click="goToAddAddress">新增地址</van-button>
      </div>
    </div>
    <van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import {getAddressListApi, deleteAddressApi} from '../../api/address';

export default {
  name: "userAddress",
  components: {
  },
  data() {
    return {
      hasData: true,
      addressList: [],
      defaultAddress: null,
      loading: true,
    }
  },
  methods: {
    // 返回
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    // 滑动删除
    deleteSwtichRight(id) {
      const self = this;
      self.$dialog.confirm({
        title: '确定删除',
        message: '该操作无法撤回，请确定是否继续操作？'
      }).then(() => {
        self.deleteAddress(id);
      }).catch(() => {
        self.$notify({
          message: '已取消操作',
          background: '#00BD71',
        })
      });

    },
    // 删除地址
    deleteAddress(id) {
      const self = this;
      self.loading = true;
      deleteAddressApi(id).then(response => {
        if (response.code === 1) {
          self.$notify({
            message: '删除成功！',
            background: '#00BD71'
          });
          // 刷新列表
          self.getAddressList();
          self.loading = false;
        } else {
          self.$notify(response.data);
          self.loading = false;
        }
      }).catch(error => {
        console.log(error);
        self.loading = false;
      })
    },
    //获取地址数据
    getAddressList() {
      const self = this;
      getAddressListApi().then(response => {
        if(response.code === 1) {
          if( response.data.list.length !== 0 ) {
            self.hasData = true;
            self.addressList = response.data.list;
            self.defaultAddress = response.data.default_id;
          } else {
            self.hasData = false;
          }
        }
        self.loading = false;
      }).catch(error => {
        console.log(error);
        self.loading = false;
      })
    },
    // 默认地址
    defalutAddressItem(id) {
      const self = this;
      let dom = '';
      if(id === self.defaultAddress) {
        dom = '<span class="van-tag van-tag--plain van-hairline--surround" style="background-color: rgb(232, 255, 242); color: rgb(0, 189, 113);">默认</span>'
      }

      return dom;
    },
    // 添加地址
    goToAddAddress() {
      const self = this;
      self.$router.push({
        path: '/user/addressAdd'
      })
    },
    // 修改地址
    editAddress(id) {
      const self = this;
      self.$router.push({
        path: '/user/addressAdd',
        query: {
          id: id
        }
      });
    },
  },
  created() {
    const self = this;
    self.getAddressList();
  }
}
</script>

<style lang="less">
.yg-user-address {
  .user-address-list {
    .address-item {
      .address-title {
        color: #333;
        font-size: 16px;
        font-weight: 600;
      }
      .address-sub-title {
        color: #333;
        font-size: 12px;
      }
    }
    .custom-icon {
      align-self: center;
      font-size: 18px;
    }
    .van-swipe-cell__right {
      width: 65px;
      height: 100%;
      .yg-swtch {
        background-color: #FA4E1B;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        .title {
          align-self: center;
          color: #fff;
          font-size: 14px;
        }
      }
    }

  }

  .yg-address-list_add {
    position: fixed;
    left: 0;
    bottom: 0;
    background-color: @mainColor;
    border: 0.02667rem solid @mainColor;
  }
}
</style>

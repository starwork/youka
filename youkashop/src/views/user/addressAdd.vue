<template>
  <div class="yg-user-address-add">
    <van-nav-bar class="yg-nav-bar" title="新增收获地址" left-arrow @click-left="onClickLeft"/>
    <van-address-edit
      :area-list="areaList"
      :address-info="addressInfo"
      show-set-default
      @save="onSave"
      ref="addressEditRef"
    />
    <van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import area from '../../utils/area'
import {addressApi, getAddressInfoApi, editAddressApi} from '../../api/address'

export default {
  name: "addressAdd",
  components: {},
  data() {
    return {
      areaList: area,
      loading: false,
      addressInfo: {
        id: null,
        name: null,
        tel: null,
        province: null,
        city: null,
        county: null,
        areaCode: null,
        addressDetail: null,
        isDefault: null,
      },
    }
  },
  computed: {
  },
  methods: {
    // 返回
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    // 保存按钮
    onSave(content) {
      const self = this;
      if (self.$route.query.id !== undefined) {
        // 修改
        self.editAddress(content);
      } else {
        // 新增
        self.addNewAddress(content);
      }
    },
    // 查询地址
    getAddressInfo(id) {
      const self = this;
      self.loading = true;
      getAddressInfoApi(id).then(response => {
        if(response.code === 1) {
          self.addressInfo.id = response.data.detail.address_id;
          self.addressInfo.name = response.data.detail.name;
          self.addressInfo.tel = response.data.detail.phone;
          self.addressInfo.province = response.data.detail.region.province;
          self.addressInfo.city = response.data.detail.region.city;
          self.addressInfo.county = response.data.detail.region.region;
          self.addressInfo.addressDetail = response.data.detail.detail;
          self.addressInfo.isDefault = response.data.is_default === 1;

          // 在页面显示省市区
          for (const areaKey in area.county_list) {
            if(area.county_list[areaKey] === response.data.detail.region.region) {
              // console.log(key);
              self.addressInfo.areaCode = areaKey
            }
          }

        }
        self.loading = false;
      }).catch(error => {
        console.log(error);
        self.loading = false;
      })
    },
    // 判断新增还是修改
    judgePage() {
      const self = this;
      if (self.$route.query.id !== undefined) {
        // 修改
        self.getAddressInfo(self.$route.query.id)
      }
    },
    // 新增
    addNewAddress(content) {
      const self = this;
      self.loading = true;
      addressApi({
        region: `${content.province},${content.city},${content.county}`,
        name: content.name,
        phone: content.tel,
        detail: content.addressDetail,
        is_default: content.isDefault ? 1 : 0
      }).then(response => {
        if (response.code === 1) {
          self.$notify({
            message: '添加成功！',
            background: '#00BD71'
          });
          // 返回列表
          setTimeout(function () {
            self.onClickLeft();
          }, 1500)

        } else {
          self.$notify(response.data)
        }
        self.loading = false;
      }).catch(error => {
        console.log(error);
        self.loading = false;
      })
    },
    // 修改
    editAddress(content) {
      const self = this;
      self.loading = true;
      editAddressApi({
        region: `${content.province},${content.city},${content.county}`,
        name: content.name,
        phone: content.tel,
        detail: content.addressDetail,
        is_default: content.isDefault ? 1 : 0,
        address_id: self.$route.query.id
      }).then(response => {
        if (response.code === 1) {
          self.$notify({
            message: '修改成功！',
            background: '#00BD71'
          });
          // 返回列表
          setTimeout(function () {
            self.$router.push({
              path: '/user/address'
            });
          }, 1500)

        } else {
          self.$notify(response.data)
        }

        self.loading = false;
      }).catch(error => {
        console.log(error);
        self.loading = false;
      })
    },
  },
  created() {
    const self = this;
    self.judgePage();
  },
  mounted() {
    const self = this;
  }
}
</script>

<style lang="less">
.yg-user-address-add {
  .van-switch--on {
    background-color: @mainColor;
  }

  .van-button--danger {
    background: @mainGradientColor;
    border: none;
  }
}
</style>

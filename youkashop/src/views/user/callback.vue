<template>
    <div id="myInvitation">
         <van-nav-bar class="yg-nav-bar" title="意见反馈" left-arrow @click-left="onClickLeft"/>
         <p class="p_title">请选择你想要反馈的问题点</p>
         <div class="questions_radio">
             <van-radio-group v-model="radio">
                <van-cell-group>
                    <van-cell title="功能异常：功能故障或不可用" clickable @click="radio = '功能异常：功能故障或不可用'">
                    <van-radio name="功能异常：功能故障或不可用" />
                    </van-cell>
                    <van-cell title="产品建议：用的不爽，提建议" clickable @click="radio = '产品建议：用的不爽，提建议'">
                    <van-radio name="产品建议：用的不爽，提建议" />
                    </van-cell>
                    <van-cell title="安全问题：密码、隐私、欺诈等" clickable @click="radio = '安全问题：密码、隐私、欺诈等'">
                    <van-radio name="安全问题：密码、隐私、欺诈等" />
                    </van-cell>
                    <van-cell title="其他问题" clickable @click="radio = '其他问题'">
                    <van-radio name="其他问题" />
                    </van-cell>
                </van-cell-group>
            </van-radio-group>
         </div>
         <p class="p_title">详细描述你的问题和意见</p>
         <div class="questions_detail">
             <van-cell-group>
                <van-field v-model="value" rows="6" type="textarea"  placeholder="请输入不少于1个字的描述" />
             </van-cell-group>
             <div class="detail_num">
                 {{wordNumber}}/{{totalNumber}}
             </div>
         </div>
         <div class="questions_submit">
             <van-button type="primary" @click="submit" size="large">提交</van-button>
         </div>
               
    </div>
</template>
<script>
import {submitCallback} from '../../api/data'
export default {
    name:"myInvitation",
    data(){
        return{
            radio:'其他问题',
            value:'',
            totalNumber:300
        }
    },
    methods:{
        /**
         * 返回
         */
        onClickLeft() {
        const self = this;
        self.$router.go(-1);
        },
        // 页面跳转
        goToPage(pageName) {
        const self = this;
        self.$router.push({
            path: pageName
        })
        },
		submit(){
			const self = this;
			if(self.radio == ''){
				self.$dialog.alert({
					message: '选择问题点'
				});
				return false;
			}
			if(self.value == ''){
				self.$dialog.alert({
					message: '输入反馈内容'
				});
				return false;
			}
			
			submitCallback(self.radio,self.value).then(res => {
				if(res.code == 1){
					self.$notify({
					  message: '反馈成功',
					  background: '#00BD71'
					});
					self.onClickLeft();
				}
			})
		},
    },
    computed:{
        wordNumber(){
            if(this.value.length >= this.totalNumber){
                this.value = this.value.substring(0,this.totalNumber);
            }
            return this.value.length;
        }
    }
}
</script>
<style lang="less" scoped>
    #myInvitation{
        .p_title{
            margin: 10px 0 10px 20px;
            font-size: 14px;
            color: #999999;
        }
        .questions_radio{
            .van-cell{
                flex-direction: row-reverse;
                .van-cell__title{
                    flex: 12
                }
                .van-cell__value{
                    flex: 1;
                    padding-right: 15px;
                }
            }
            
        }
        .questions_detail{
            position: relative;
            .detail_num{
                position: absolute;
                bottom: 10px;
                right: 10px;
                font-size: 14px;
                color: #999999;
            }
        }        
        .questions_submit{
            display: flex;
            justify-content: center;
            .van-button--primary{
                background: #00BD71;
                margin-top: 20px;
                width: 94%;
            }

        }
        
    }
</style>
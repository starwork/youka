<template>
    <div class="wealthAlone">
        <van-nav-bar class="yg-nav-bar" title="单品数量详情" left-arrow @click-left="onClickLeft"/>
         <div class="aloneDetail">
           <select v-model="aloneSelected" @change="getAloneSelected">                                        
                <option :value="item.amount" v-for="(item,index) in aloneList" :key="index" >{{item.name}}</option>                                    
            </select>
            <div class="al_con">
                <div class="al_title">
                    <p class="al_type">奖励类型</p>
                    <p class="al_amount">单品业绩</p>
                    <p class="al_price">奖金(元)</p>
                </div>
                <div class="al_content">
                    <ul>
                        <li v-for="(item,index) in aloneSelected" :key="index">
                            <p>{{item.type}}</p>
                            <p>{{item.amount}}</p>
                            <p>{{item.price}}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name:"aloneAmount",
    data(){
        return{
            aloneSelected: '',
            aloneList:[
                    {
                        id: '0',
                        name: '2019年03月',
                        amount:[
                            {
                                type:"分销",
                                amount:1,
                                price:1200
                            },
                            {
                                type:"分销2",
                                amount:3,
                                price:1400
                            },
                            {
                                type:"分销",
                                amount:1,
                                price:1200
                            }
                        ]
                    },
                    {
                        id: '1',
                        name: '2019年04月',
                        amount:[
                            {
                                type:"分销3",
                                amount:1,
                                price:1200
                            },
                            {
                                type:"分销2",
                                amount:3,
                                price:1400
                            },
                            {
                                type:"分销5",
                                amount:1,
                                price:1200
                            }
                        ]
                    },
                    {
                        id: '2',
                        name: '2019年05月',
                        amount:[
                            {
                                type:"分销",
                                amount:1,
                                price:1200
                            },
                            {
                                type:"分销26",
                                amount:3,
                                price:1400
                            },
                            {
                                type:"分销4",
                                amount:1,
                                price:1200
                            }
                        ]
                    },
                     {
                        id: '3',
                        name: '2019年06月',
                        amount:[
                            {
                                type:"分销6",
                                amount:1,
                                price:1200
                            },
                            {
                                type:"分销2",
                                amount:3,
                                price:1400
                            },
                            {
                                type:"分销8",
                                amount:1,
                                price:1200
                            }
                        ]
                    }
                ],
        }
    },
    created(){
        this.aloneSelected = this.aloneList[0].amount;
    },
    methods:{
    /**
     * 返回
     */
    onClickLeft() {
      const self = this;
      self.$router.go(-1);
    },
    // 月份选择
    getAloneSelected(){
        const self = this;
        console.log(self.aloneSelected)
    }
    }
}
</script>

<style lang="less">
    .wealthAlone{
        .van-nav-bar{
          
        }
        .aloneDetail{
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #FFFFFF;
            margin: 10px 0;
            select{
                border: none;
                margin-top: 10px;
            }
            .al_con{
                display: flex;
                flex-direction: column;
                width: 100%;
                .al_title{
                    display: flex;
                    background: rgb(175, 174, 174);
                    justify-content: center;
                    margin-top: 10px;
                    p{
                        flex: 1 1 33.33%;
                        text-align: center;
                        padding: 0;
                        font-size: 14px;
                    }
                }
                .al_content{
                    display: flex;
                    justify-content: center;
                    ul{
                         width: 100%;   
                        li{
                            display: flex;
                            
                            p{
                                flex: 1 1 33.33%;
                                text-align: center;
                                padding: 0;
                                font-size: 14px;
                            }
                        }
                    }
                    
                   
                }
            }
        }
    }
</style>
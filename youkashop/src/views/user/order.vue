<template>
  <div class="yg-order">
    <van-nav-bar class="yg-nav-bar" title="我的订单" left-arrow @click-left="onClickLeft"/>
    <div class="order-content">
      <van-tabs v-model="active" sticky @click="tabsFun">
        <van-tab title="全部">
          <div v-if="hasData" class="order-list">
            <div class="item" v-for="(item, index) in orderList_0" :key="index">
              <div class="flex-row between item-header" @click="toDeatil(item.order_id)">
                  <div>订单号：{{item.order_no}}</div>
                  <div v-if="item.pay_status.value == 10 && item.order_status.value == 10"  class="status-name">等待付款</div>
                  <div v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10"  class="status-name">等待卖家发货</div>
                  <div v-if="item.delivery_status.value == 20 && item.order_status.value == 10"  class="status-name">卖家已发货</div>
                  <div v-if="item.order_status.value == 30"  class="status-name">交易成功</div>
              </div>
              <div class="goods-list" @click="toDeatil(item.order_id)">
                <div class="goods-item" v-for="(good,key) in item.goods">
                  <img :src="good.image.file_path" alt="">
                  <div class="goods-content">
                    <div class="goods-name">{{good.goods_name}}</div>
                    <div class="goods-desc">{{good.goods_attr ? good.goods_attr : ''}}</div>
                    <div class="goods-price">¥{{good.goods_price}} <span>x{{good.total_num}}</span></div>
                  </div>
                </div>
              </div>
              <div class="total" @click="toDeatil(item.order_id)">
                  <div class="goods_num">共{{item.goods_num}}件商品</div>
                  <div class="label">合计：</div>
                  <div class="price">¥{{item.total_price}}</div>
              </div>
              <div class="button">
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="cancel(item.order_id)" class="cancel">取消订单</van-button>
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="pay(item.order_id)" class="payment">立即付款</van-button>
                  <van-button v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10" plain size="small" @click="delivery(item.order_id)" class="payment">提醒发货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="receipt(item.order_id)" class="payment">确认收货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="express(item.order_id)" class="cancel">查看物流</van-button>
                  <van-button v-if="item.order_status.value == 30 && item.comment_status == 10" plain size="small" @click="comment(item.order_id)" class="payment">评价</van-button>
              </div>
            </div>
          </div>
          <div v-else class="yg-no-data text-center">
            <div class="img">
              <img src="data:image/png;base64,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" alt="没有数据">
            </div>
            <div class="title">
              您还没有任何订单哦！
            </div>
          </div>
        </van-tab>
        <van-tab title="待付款">
          <div v-if="hasData" class="order-list">
            <div class="item" v-for="(item, index) in orderList_1" :key="index">
              <div class="flex-row between item-header" @click="toDeatil(item.order_id)">
                  <div>订单号：{{item.order_no}}</div>
                  <div v-if="item.pay_status.value == 10 && item.order_status.value == 10"  class="status-name">等待付款</div>
                  <div v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10"  class="status-name">等待卖家发货</div>
                  <div v-if="item.delivery_status.value == 20 && item.order_status.value == 10"  class="status-name">卖家已发货</div>
                  <div v-if="item.order_status.value == 30"  class="status-name">交易成功</div>
              </div>
              <div class="goods-list" @click="toDeatil(item.order_id)">
                <div class="goods-item" v-for="(good,key) in item.goods">
                  <img :src="good.image.file_path" alt="">
                  <div class="goods-content">
                    <div class="goods-name">{{good.goods_name}}</div>
                    <div class="goods-desc">{{good.goods_attr ? good.goods_attr : ''}}</div>
                    <div class="goods-price">¥{{good.goods_price}} <span>x{{good.total_num}}</span></div>
                  </div>
                </div>
              </div>
              <div class="total" @click="toDeatil(item.order_id)">
                  <div class="goods_num">共{{item.goods_num}}件商品</div>
                  <div class="label">合计：</div>
                  <div class="price">¥{{item.total_price}}</div>
              </div>
              <div class="button">
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="cancel(item.order_id)" class="cancel">取消订单</van-button>
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="pay(item.order_id)" class="payment">立即付款</van-button>
                  <van-button v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10" plain size="small" @click="delivery(item.order_id)" class="payment">提醒发货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="receipt(item.order_id)" class="payment">确认收货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="express(item.order_id)" class="cancel">查看物流</van-button>
                  <van-button v-if="item.order_status.value == 30 && item.comment_status == 10" plain size="small" @click="comment(item.order_id)" class="payment">评价</van-button>
              </div>
            </div>
          </div>
          <div v-else class="yg-no-data text-center">
            <div class="img">
              <img src="data:image/png;base64,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" alt="没有数据">
            </div>
            <div class="title">
              您还没有任何订单哦！
            </div>
          </div>
        </van-tab>
        <van-tab title="待发货">
          <div v-if="hasData" class="order-list">
            <div class="item" v-for="(item, index) in orderList_2" :key="index">
              <div class="flex-row between item-header" @click="toDeatil(item.order_id)">
                  <div>订单号：{{item.order_no}}</div>
                  <div v-if="item.pay_status.value == 10 && item.order_status.value == 10"  class="status-name">等待付款</div>
                  <div v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10"  class="status-name">等待卖家发货</div>
                  <div v-if="item.delivery_status.value == 20 && item.order_status.value == 10"  class="status-name">卖家已发货</div>
                  <div v-if="item.order_status.value == 30"  class="status-name">交易成功</div>
              </div>
              <div class="goods-list" @click="toDeatil(item.order_id)">
                <div class="goods-item" v-for="(good,key) in item.goods">
                  <img :src="good.image.file_path" alt="">
                  <div class="goods-content">
                    <div class="goods-name">{{good.goods_name}}</div>
                    <div class="goods-desc">{{good.goods_attr ? good.goods_attr : ''}}</div>
                    <div class="goods-price">¥{{good.goods_price}} <span>x{{good.total_num}}</span></div>
                  </div>
                </div>
              </div>
              <div class="total" @click="toDeatil(item.order_id)">
                  <div class="goods_num">共{{item.goods_num}}件商品</div>
                  <div class="label">合计：</div>
                  <div class="price">¥{{item.total_price}}</div>
              </div>
              <div class="button">
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="cancel(item.order_id)" class="cancel">取消订单</van-button>
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="pay(item.order_id)" class="payment">立即付款</van-button>
                  <van-button v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10" plain size="small" @click="delivery(item.order_id)" class="payment">提醒发货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="receipt(item.order_id)" class="payment">确认收货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="express(item.order_id)" class="cancel">查看物流</van-button>
                  <van-button v-if="item.order_status.value == 30 && item.comment_status == 10" plain size="small" @click="comment(item.order_id)" class="payment">评价</van-button>
              </div>
            </div>
          </div>
          <div v-else class="yg-no-data text-center">
            <div class="img">
              <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOwAAACuCAYAAAA1Q+FzAAAWaUlEQVR4nO2de5RlRXXG7zDM8HB4ycMIIgrEByhB5ZGIQoKgBlkjDwcWGBURXOggGRBNEINAlABGxaUCMoAGJQq6gpgEQYe0QAQBpbvpPvXtb9e9Y6Yb2+EqAgMijxkqf8xpaI7nPrrn3lP33rN/a+1/us85tau6vq73rkrF6DlWrVq1jaqeICI3ACCAx1KjiNygqiesWrVqm9h+GkapGRoa2hTAWSQfEZHQzEg+AuCsoaGhTWP7bRilY2RkZCcA97YSatYA3DsyMrJTbP8NozSMjIzsRPKB2Yp1Rmv7gInWMAog7Qb/ScsKYA3Jc1R173q9vqhery9S1b1JngNgTV5La91jw+gyAM7KaTVvdc7t0ugd59wuInJrjmjPKtJ3wygVq1at2iZngunWEMK8Vu+GEOZlRUvyEZs9NowukS7dvKAb3KxlzeKc2yXbPVbVE7rosmGUFxG5IdNCnjPbb5A8J9NC39ANXw2j9KQbIWa2jnvP9huqunemlWY3fDWM0gPgsZliq9fri2b7jXq9vigj2Me64athlJaJiYkdVfUiks92WrAk1wH4lE0+GcYGkiTJngCuBvBU3gaITnSJZ7a0qvql2UxiGYZRqVS89weR/K9si5qzY2kuk06fabF18RkA187ln4FhlIYQwkbe+/eIyD2z2Be8wcs6Lf4h/Ng5d2g3820YfcXk5ORmJD9KstpCnEry9E5unFDVR0h+BsCvWwh3GMDxIYSNiygTw+g5SG6Xdk9/20Isd6nqUSGEjSqV7mxNDCEsFJEPkkxa/NNY5ZxbNpfJLsPoS7z3u4nI10g+0USkzwK4keRbsu93c/N/CGGeqr6L5G0thPt7kp9LkuTPiis5wygQkvuKyPUk1zUR6pMiciXJ1zT7VhHH65Ik2Q/A99rwd3krfw2jLwghzHPOHQbgpy0E9DDJC2bTYhV1gN17v5uqXtqqRyAiPxgfHz9gbiVlGBEJISxU1RNIjrcaE5I8fa5jwiJDxJDcDsC5rcbcAO50zh05PeY2jJ6lVqttpaqfaGPWdcR7/95OzboWGYQtndVeSrLWQrhU1Q/bIXmj50i7p58n+WgLof7Ee//22P52ghDCfBFZIi3WjUk+qKqfnpycfHFsn42S45x7HclvAni6SUvzDMl/996/Iba/3QLAX5P872Y7swA8DuDLAF4R21+jZFSr1b8RkZtadAkfd85dUqa9uUmS7EnyG432Pqct7lqS33HOvTG2v8YAE0KYnyTJMa1mZkmuVtWzy3z6ZWRkZCfn3MWthggisgLAO2L7awwQU1NTmwM4VURWtmhRBcDJ3vtNYvvcK3jvt1TVM9tYMx5NkuTvQggLYvts9Cne++0BnAfgdy2E+jOSR7Szn7eshBAWAPgAgLEWvZMJkmeIyBaxfTb6hGq1urtz7rI2NgrcUK1W3xzb336D5N8CGGoh3IcB/Itz7qWx/TV6FFXdH8D3W23FI3mFiLw6tr/9Tq1W20dEriO5tknv5Snn3FWq+trY/ho9QAhhHoDDSd7eotv7e+fcZ1euXPmS2D4PGqq6K4CvAvhDsx4NgB8mSfLW2P4aEQghLPTen0jStRDq/4nI369evfpFsX0edABsS/IckvUW3eWfAzjatj6WgOHh4a2dc/8AYKpFpbhPVY+zA9vFMzk5uZlz7iMkfYu/kReRUyYnJzeL7bPRYarV6s4AvtAqPAqAW1T1kNj+GutD5gA4muTPWwi3np753Ta2z8YGMjY2tpeIXNNq6yCAb4+Pj/9FbH+NfFT1QJL/2WLr4x9IfmV0dPSVsf0tDd77l5FcKiI3TZ86abHgbmbWUZtxyukmkku99y+LrYueY2JiYkdZH7mg4RS/mVkMS+vk8omJiR1j66QnEJHFswm9aWYWw9I6uji2XqKiqqc126hgZtZLRnKdqp4WWzdREJHFeWIlOe6cW+a938PCaRpFU6/XF3nv93DOLcsL8ZPW2XK1tBMTEztmu8EAniK51BbEjV4hhLARyaXZ870A1pRqTCsiy7NiTZLk4Nh+GUYeSZIcnHMof3lsvwohXbpZm+lmLI3tl2E0I11unFln15ZiyScn4+PWDTZ6nbR7PF66hkYysZCcc8ti+2QY7eCcW5bpFt8U26euk+4ieS7T3vs9YvtkGO3gvd8jM/fC2D51nex2Q1u6MfqFer2+KCPYx2L71HWy61qx/TGM2VC6+lu6DBsDRenqb+kybAwUpau/pcuwMVCUrv6WLsPGQFG6+lu6DBsDRenqb+kybAwUpau/pcuwMVCUrv6WLsPGQFG6+lu6DBsDRenqb+kybAwUpau/pcuwMVCUrv6WLsPGQFG6+lu6DBsDxUDU3xDCAhFZAuDaXo/YPx3ZHcC1IrIkhLAgdvkZ/UPfC9Y5dyTJamwhztVIVp1zR8YuR6M/6FvBhhA2cs5dHFtwnTLn3MUWW8poRd8KdpDEOlO0scu1FxgeHt46vZn+dOfcR0Tk1bF96hX6UrDOuSNzupbPkrxCVffv5bAv9Xp9karuT/KKvOsLy949TsOgrMyWS2mvqMjQd4INISzIjlkBTPVjIPA0QPRU5h9PtawTUSGEBQCuzut9kLw9tn+9QN8JVkSWZFvWfhTrNEmSHJzT0i4pIu0QwrwkSd4tIj8QkRUxDcDPSD7RaLgA4NwiyqTX6TvBpsshMwV7Rd5zIYSFqnohgCkAU6p6YQhhYbvpFPk+ySsylfPadtPZEPKGFr1oJB+YnJx8cRFl0uv0o2BfEFdYVffPe05VL8wZB13YbjpFvq+q+2cEW0i8WRG5IbYYWxmAp/u5B9Vp+lGwbcUVzo4N0z/+1CzSKez9WPFmJXMLQq9ZegdSIcODfsEE2zidgResqh4XW5RNWtZfOecOLaIc+ol+FKx1iTtECGG+rN/SeaOI3NoDtgLA5d77E6empjYvogz6jX4UrE06GaWl7wQrtqxjlJi+E6xtnOi8DyJyjayfMe45A/BtAO8IIcwrqkx6mb4TbKViWxM7hYgszqbfq6aqJxRVLr1MXwq2Uune5n+S9Wq1unOjdKvV6s4k691Iu+jN/9IH67DTBuDOIsumV+lbwXb5eN093vtNsml67zcRkXu6Jdaij9eJyP/GFmK7RrJaZNn0Kn0r2Gm6eIB9eTYtEVnejYoY64SOCbb/6HvBVirdCxED4OTpNACc3KFv9kyIGBNs/zEQgp0NjTJcq9W2AqCZSvIkyX1TezIjPNZqta1i5mVDaSZYAPeTPMN7f2IR5pxbBuBeE2xzTLAzSJJkTwCPZyrKBMmJbCuZJMmesfLQKRoJluRdEX3KnQgzwa7HBJshSZJjWnXPkiQ5JobvnaaRYJ1zH4rlk6q+ywTbGBNsDgA+36Sr+Pmife4WAH7YQBxvieVTtVrdvYFPd8fyqZcwweYQQphP8uvZjQ0AvjhIkQ3zNqAA0LxdRd7795D8CQAl+WOSR8w2PVU9BMAPASiAOwGcnFeeInJHL7X6vYQJtgm1Wu31zrllqnpmrVZ7fVE+Fkm6PfJqEbneOffJ0dHRHbLPeO/3yGnxngXwqnbT8d5vT/KPOa3n4uyzw8PDW6vqx0TkepLfsK2Jz2OCNVoC4NwGw4Nz2/2GiJzSYJhxXRddHzhKV39Ll+EOAODmPLGp6kXtfoPk6Q0Eu3KQhhndpnT1t3QZ3kBU9cC8QwrpuPKT7X6H5PsaTeSRPMm6vO1Ruvpbugy3gOS+qnqpiHwrayTvbiSytIU9sN10ALyq2bcASJ4PAC4fHx8/oJtl0E+Urv6WLsNNSJLknc1E1MxITgwNDW3ablohhHkA7p9regCO72ZZ9Aulq7+ly3ATAHx/rgJS1XfNNr0kSfYjuW6Oad7RjTLoRVT1KABDJL/uvd9y5u9KV39Ll+EmyBw3/zvnPjvXNJ1zy+bYopdip5Oq7grgqRllfdnM35eu/pYuw02QORwXVNXzNzRdkv/YaCKrSZf4xk7kudfJRtsE8PjMVrZ09bd0GW7C2NjYXgAeareFI/m2TqU9Pj5+QLtjWpKPlGHiKYSwMcnVOWVwyvQzpau/pctwC4aGhjZV1aOcc+/PM1U9SlV37Vb61Wp15yRJ3t0ofQBHlyVGsaoe1aB38cvpZ0pXf0uXYaNvEJEfNZk3eGP6TLnqb+kybPQFtVrt5c1m0Kcnn0pXf0uXYaMvAHBephu8JjOOf3Rqamrz0tXf0mW4Cd77TdI9vqMisrIXDcCYc+6TgzyODSFsRHIyI9CPAvhNpr5+sHT1t3QZbkJeBehVU9VPxC6vbpGNspGGKdqC5AWZn99Zuvpbugw3AcAtsYU4CxuNXV7dQkR+kMnrlZVKpeK9363VenVs37tO6TLcBMmJ7NCrNqg7nZxzLwXwTKY38dwVqiKywgRbpgw3oVnsql4zkt+MXV7dAMCnZuYTwP0zf0/yWBNsmTLchNHR0VeSdLHF2MoAqHPudbHLq9OEEOaRrGVa149lnlkI4Hcm2LJkuAUhhHmqur+qHjJbS5Lknap6aTaWc0Zsz5D8jogsnksaAP5qUA+3k3xbphfxx1WrVm2TfQ7AF0ywZclwAajqmU1ayCtj+9eriMh3M2X1rbznVPW1JtiyZLgARkdHd2hUobz3B8X2rxchud3MY3Rpd7hhBA9pcBSySJ+jULoMF0B6GVmuYMfGxvaK7V8vQvKMzNCBzZ4H8AETbBkyXAAm2NkjIsi0rk03hkxOTm5G8pHS1d/SZbgATLCzg+RbMq3r03kB3LOIyNdKV39Ll+ECMMHODpL/lhHs99p5T1X3zswqP1ur1V7ebX+jYoLtPCbY9hkeHt6a5BOZibm3t/t+9g5dAOd109/omGA7jwm2fUguzQjuV7NZZ1bVD2da2ckQwvxu+hwVE2znMcG2x+rVq19EspqZbPr0bL4hIltkN6oM9M1+JtjOMzQ0tGkjwXrv3xDbv9jUarWtSH40K1aST05MTOw42+8BuDzznXUArk6SZL9u+B8VE2znybtndkaX71Ox/YvF+Pj4ASS/mR2zziibs+byXZLbkaw3KPNRAKcODw9v3en8RMEE21m89weRHGki2F+TPGKgx1kzSMV0RhuHKu4IIWw813Scc0cCeLrR99N/EtckSfLWTuavcEywz5OOqT4HYBWAh2ZreQv5TYT72BzTmHTOXdzLLUZ66uZtIvLd7HbDHCE9CeCsDRHrNGlc6V+0UfYC4OPe++07kd9CMcE+j3PuI+0KLrY55/4pdnllSQ+fn5U9ItdANL9S1U+PjIzs1EkfQgjzARwO4EaSa1v48JSIXKeqh/TNCSgT7PNIi2gGvWQkx2OXV6WyPmCac+4wEbkhGykiRyBPA/ie9/7tRQhkYmJiR1U9W9YHsGtVpitV9ey5THoVign2eUjeHluI7RoAjVlWtVrt5QDOJTnRhq9U1U+0s92wG4QQ5jnnDhWR69rooq8FcCOAw3tynsEE+zwkz4ktxFm0sF8punxCCBunM+A3tbomk+QfAXy7144Teu+3V9Uz0wuzW5XxA6p6vnNul9h+P4cJ9nlWrlz5EhH50WxvkotgK4qsRKq6q6pe2OBiqmxrer+qnpYXKaLXSJLkrSJyTaNlphnCXQfgZgBHR291TbB/inPupdVqdfdetKLHWCSPaNWNBPC4c+4q7/1fFulbpxgeHt4awKmyPoB8q1b39k7MaM8ZE6zRDABDTYT6WLoXeIvYfnaKJEn2I/mTZqKtVqtvjuagCdZoBskrWrSuvyF5gfd+t9i+bgghhIVJkhxD8sfNhkQkn+z0UtSsMMEazajVals55y5rFgkyrcjPisgKkseGEBbG9rtdSL6G5L+KyG/b6A7fB+AdUR02wRrt4L3fUkROAfDLNiaefgfgC6r62th+55GGl3lfO8t4ANaQ/HqtVtsntt+VSsUEa8we59wbnXOXkXy0jRntOwB8YHJycrPYfo+Nje1F8iskH26jNb3Le3/i6tWrXxTb7xdggjXmSno/6wfzbpHLEcAjIvI1Vd27SB/r9foikieRvLuN1vT3AL7c07cqmGCNTpAkyZ7OuUsAPNSGMO5NI0V0bXaZ5L4krwDwWBv+/NR7/96hoaFNu+VPxzDBGp3Ee7+Jqh4H4H9abUAB8DiAy0lu16n0RWRJs+ONM1r8B51zFwN4VafSLgQTrNEtqtXq7u3skCJZd84duSFpAdiW5H+0SGcdgFu89+8JISzoVD4LxQRrdJsQwgJVPUrWb/vM3YMM4OkNiXfVTKwkH3DO/TOAV3QwW3EwwRpFkp7yOY/kZI5ofzGXvboisiRHpL196maumGCNGIQQ5jvnPpRtcQEcPttvSWYPMMnbqtXqzt3wOzomWCMmAK7OCPbG2byfJMl+2ZZ1YMVaqZhgjbhkBQfgmdmcSBKR5Rsi+L7DBGvEJtulVdWz23mvXq8vyq6zzqVL3VeYYI3YpGdRZ9bDle3EfAJwcnY2eKAmmPIwwRqxybsQyzl3aKv3ROSeTMt8fhH+RiXbpajX64ti+2SUDxG5JtN4XN/s+bGxsb2ymyJ6KvZStwDAmRn33u8R2yejfKTxlWaORZ9qFugbwFczz99cpL/REJGbMl2RZbF9MspJNpIhgI/nPZeeZ3048+zRRfsbhez9nCTHQwgbxfbLKB8APp4RoeQ955x7f6bOPti3e4Nni/f+ZdkrDUguje2XUT6899tnIzTmXV6VjRShqhfF8DcaOYvPTyVJcnBsv4zyISLXZSafrpn5e5Kvya5s1Gq1P4/lbxQmJiZ2BLAmK1qSS617bBSJqh6S6e09MfOmvjRg2sx6OhTL19HR0R1qtdo+UdZ+RWRx3tEnkuPOuWXe+z1sycfoNiGEeZK5vArAqenvFkomuiGA42P46b0/CMAfUo3cFiW4uKqe1urOFDOzAiwbcnS0UqlUkiQ5ZubPATwUK6xLNo4VyWNj+FERkcXZ7rGZWWzLi8gP4MsxNJIkyZ45Pq6I4UulUlk/phWR5a0uxDUzK8rSGwZeECMqVnRDVf1S1j+Sz6rqrjH8eY50yWepiNwEgO1EoDMzK8JI3hVJE5s0igxJ8oIYPhlGdEII80k+0Eiw3vsTY/ilqsc18gnAVNSb7QwjJqp6fgNhrIkRkT+EsDHJ+1q0/CcV7Zdh9ATOuV3yVi0AXF6kH9OxlrNinQ6bmvnZWlW9dEMiQBpG3wLg5qxgq9Xqm4pIO0mSPVX1S43GrAC+mMZDfrBBa3s3yZNs/4JRGgAcnRHBfd1OU1UPBPCzFl3fZGpqavP0+aMAPNNkbLsGwFe991t223fDiEo6+XRbKpInu30/6+jo6A7TO5gaCHUdgC9Oi3WaarX6JsnEpsqac+6qbvpuGD1BCGHjarX65iJuPq/Vavs0Ex2AWwBsm/euc+6wZldwAriz2/4bRqlIZ4KbXvScXqT13F1A9Xp9kXPuqhbvrI2179kwBppUtMeKyIpGN/ABeGZ68quZWAE85Jy7pFdvozeMgUJVdyV5AYCpHEGOOucOayDUIQDH98Wds4YxaKSt7kk50VkezQj1l9aaGkaPoKqXNhurmlgNo4fIxkPOdoNj+2cYRgaSdzcQrM0CG0avQfKkvNlg7/0msX0zDCNDemveC6KyOOcuie2XYRgNmHlVSHpCxyabDKNX8d5vKSJXArjTe//e2P4YhtEF/h+liuA9fQ9SPgAAAABJRU5ErkJggg==" alt="没有数据">
            </div>
            <div class="title">
              您还没有任何订单哦！
            </div>
          </div>
        </van-tab>
        <van-tab title="待收货">
          <div v-if="hasData" class="order-list">
            <div class="item" v-for="(item, index) in orderList_3" :key="index">
              <div class="flex-row between item-header" @click="toDeatil(item.order_id)">
                  <div>订单号：{{item.order_no}}</div>
                  <div v-if="item.pay_status.value == 10 && item.order_status.value == 10"  class="status-name">等待付款</div>
                  <div v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10"  class="status-name">等待卖家发货</div>
                  <div v-if="item.delivery_status.value == 20 && item.order_status.value == 10"  class="status-name">卖家已发货</div>
                  <div v-if="item.order_status.value == 30"  class="status-name">交易成功</div>
              </div>
              <div class="goods-list" @click="toDeatil(item.order_id)">
                <div class="goods-item" v-for="(good,key) in item.goods">
                  <img :src="good.image.file_path" alt="">
                  <div class="goods-content">
                    <div class="goods-name">{{good.goods_name}}</div>
                    <div class="goods-desc">{{good.goods_attr ? good.goods_attr : ''}}</div>
                    <div class="goods-price">¥{{good.goods_price}} <span>x{{good.total_num}}</span></div>
                  </div>
                </div>
              </div>
              <div class="total" @click="toDeatil(item.order_id)">
                  <div class="goods_num">共{{item.goods_num}}件商品</div>
                  <div class="label">合计：</div>
                  <div class="price">¥{{item.total_price}}</div>
              </div>
              <div class="button">
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="cancel(item.order_id)" class="cancel">取消订单</van-button>
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="pay(item.order_id)" class="payment">立即付款</van-button>
                  <van-button v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10" plain size="small" @click="delivery(item.order_id)" class="payment">提醒发货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="receipt(item.order_id)" class="payment">确认收货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="express(item.order_id)" class="cancel">查看物流</van-button>
                  <van-button v-if="item.order_status.value == 30 && item.comment_status == 10" plain size="small" @click="comment(item.order_id)" class="payment">评价</van-button>
              </div>
            </div>
          </div>
          <div v-else class="yg-no-data text-center">
            <div class="img">
              <img src="data:image/png;base64,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" alt="没有数据">
            </div>
            <div class="title">
              您还没有任何订单哦！
            </div>
          </div>
        </van-tab>
        <van-tab title="待评价">
          <div v-if="hasData" class="order-list">
            <div class="item" v-for="(item, index) in orderList_4" :key="index">
              <div class="flex-row between item-header" @click="toDeatil(item.order_id)">
                  <div>订单号：{{item.order_no}}</div>
                  <div v-if="item.pay_status.value == 10 && item.order_status.value == 10"  class="status-name">等待付款</div>
                  <div v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10"  class="status-name">等待卖家发货</div>
                  <div v-if="item.delivery_status.value == 20 && item.order_status.value == 10"  class="status-name">卖家已发货</div>
                  <div v-if="item.order_status.value == 30"  class="status-name">交易成功</div>
              </div>
              <div class="goods-list" @click="toDeatil(item.order_id)">
                <div class="goods-item" v-for="(good,key) in item.goods">
                  <img :src="good.image.file_path" alt="">
                  <div class="goods-content">
                    <div class="goods-name">{{good.goods_name}}</div>
                    <div class="goods-desc">{{good.goods_attr ? good.goods_attr : ''}}</div>
                    <div class="goods-price">¥{{good.goods_price}} <span>x{{good.total_num}}</span></div>
                  </div>
                </div>
              </div>
              <div class="total" @click="toDeatil(item.order_id)">
                  <div class="goods_num">共{{item.goods_num}}件商品</div>
                  <div class="label">合计：</div>
                  <div class="price">¥{{item.total_price}}</div>
              </div>
              <div class="button">
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="cancel(item.order_id)" class="cancel">取消订单</van-button>
                  <van-button v-if="item.pay_status.value == 10 && item.order_status.value == 10" plain size="small" @click="pay(item.order_id)" class="payment">立即付款</van-button>
                  <van-button v-if="item.pay_status.value == 20 && item.order_status.value == 10 && item.delivery_status.value == 10" plain size="small" @click="delivery(item.order_id)" class="payment">提醒发货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="receipt(item.order_id)" class="payment">确认收货</van-button>
                  <van-button v-if="item.delivery_status.value == 20 && item.order_status.value == 10" plain size="small" @click="express(item.order_id)" class="cancel">查看物流</van-button>
                  <van-button v-if="item.order_status.value == 30 && item.comment_status == 10" plain size="small" @click="comment(item.order_id)" class="payment">评价</van-button>
              </div>
            </div>
          </div>
          <div v-else class="yg-no-data text-center">
            <div class="img">
              <img src="data:image/png;base64,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" alt="没有数据">
            </div>
            <div class="title">
              您还没有任何订单哦！
            </div>
          </div>
        </van-tab>
      </van-tabs>
    </div>
    <van-loading v-if="loading" class="yg-loading"/>
  </div>
</template>

<script>
import {getOrderListApi,CancelOrder,ReceiptOrder,PayOrder,Comment} from '../../api/order'
import {getWechatInit,isWeiXin} from '../../api/wechat';
import wx from 'weixin-js-sdk';
export default {
  name: "order",
  components: {
  },
  data() {
    return {
      hasData: true,
      orderList_0: [],
      orderList_1: [],
      orderList_2: [],
      orderList_3: [],
      orderList_4: [],
      active: 0,
      loading: false,
    }
  },
  watch: {

  },
  computed: {
		goods_title(){
			return function(goods){
				console.log(goods)
				return goods[0].goods_name
			}
		},
		goods_thumb(){
			return function(goods){
				return goods[0].image.file_path
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
		//订单取消
	cancel(order_id){
		const self = this;
		self.$dialog.confirm({
			message : '确认取消订单'
		}).then(() => {
			CancelOrder(order_id).then(res => {
				if(res.code == 1){
					self.$notify({
					  message: '订单已取消',
					  background: '#00BD71'
		});
		self.tabsFun(self.active);
				}
			})
		})
		
	},
	//订单支付
	pay(order_id){
		const self = this;
		if(isWeiXin()){
			PayOrder(order_id).then(res => {
				console.log(res)
				wx.chooseWXPay({
					timestamp: res.data.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
					nonceStr: res.data.nonceStr, // 支付签名随机串，不长于 32 位
					package: 'prepay_id='+res.data.prepay_id, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=\*\*\*）
					signType: 'MD5', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
					paySign: res.data.paySign, // 支付签名
					success: function (res) {
						if(res.errMsg == "chooseWXPay:ok"){
							self.$dialog.alert({
								message: '支付成功'
							  }).then(() => {
								  self.toDeatil(order_id)
							  });
						}else if(res.errMsg == 'chooseWXPay:cancel'){
							self.$toast('支付取消');
							self.toDeatil(order_id)
						}else if(res.errMsg == 'chooseWXPay:fail'){
							self.$toast('支付失败');
							self.toDeatil(order_id)
						}
						
					}
				});
			})
		}else{
			self.$toast('只支持微信浏览器');
		}
	},
	//提醒发货
	delivery(order_id){
		console.log('delivery')
		this.$toast('该功能正在开发中！');
	},
	//确认收货
	receipt(order_id){
		const self = this;
		self.$dialog.confirm({
			message: '你确定要确认收货吗？',
		}).then(() => {
			ReceiptOrder(order_id).then(res => {
				if(res.code == 1){
					self.$notify({
					  message: '收货成功',
					  background: '#00BD71'
					});
					self.tabsFun(self.active,'');
				}
			})
		})
	},
	//物流
	express(order_id){
		const self = this;
		// this.$toast('该功能正在开发中！');
		
		self.$router.push({
			path: '/news/logistics',
			query:{
				order_id: order_id
			}
		})
		
	},
	//评价
	comment(order_id){
		const self = this;
		self.$router.push({
			path: '/user/evaluation',
			query:{
				order_id: order_id
			}
		})
	},
	
	toDeatil(order_id){
		console.log(order_id)
		this.$router.push({
			path: '/order/detail',
			query:{
				order_id: order_id
			}
		});
	},
    /**
     * 切换标签
     */
    tabsFun(index, title) {
      const self = this;
      if(index === 0) {
        self.getOrderList('all', index)
      }
      if(index === 1) {
        self.getOrderList('payment', index)
      }
      if(index === 2) {
        self.getOrderList('delivery', index)
      }
      if(index === 3) {
        self.getOrderList('received', index)
      }
      if(index === 4) {
        self.getOrderList('comment', index)
      }
    },
    /**
     * 链接跳转
     */
    gotoIem(path) {
      const self = this;
      self.$router.push({
        path: path
      })
    },
		
    // 获取订单列表数据
    getOrderList(type, active) {
      const self = this;
      self.loading = true;
      getOrderListApi(type).then(response => {
        if(active === 0) {
          self.orderList_0 = response.data.list;
        }
        if(active === 1) {
          self.orderList_1 = response.data.list;
        }
        if(active === 2) {
          self.orderList_2 = response.data.list;
        }
        if(active === 3) {
          self.orderList_3 = response.data.list;
        }
        if(active === 4) {
          self.orderList_4 = response.data.list;
        }
        self.hasData = response.data.list.length !== 0;

        self.loading = false;
      })
    },
    // 并接字符串
    spliceStr(itemArr) {
      const self = this;
      console.log(itemArr);

      return '测试';
    }
  },
  created() {
    const self = this;
    // 初始化激活位置
    self.active = Number(self.$route.query.active);

    let type;
    if(self.active === 0) {
      type = 'all'
    }
    if(self.active === 1) {
      type = 'payment'
    }
    if(self.active === 2) {
      type = 'delivery'
    }
    if(self.active === 3) {
      type = 'received'
    }
    if(self.active === 4) {
      type = 'comment'
    }
    self.getOrderList(type, self.active)
	if(isWeiXin()){
		getWechatInit().then(res => {
			console.log(res)
			wx.config(res.data);
		})
	}
  }
}
</script>

<style lang="less">
.item-header{
  font-size: 14px;
  padding: 0 14px;
  height: 45px;
  line-height: 45px;
  .status-name{
    font-size: 13px;
    color: #00BD71;
  }
}
.total{
  height: 34px;
  line-height: 34px;
  margin: 0 17px;
  display: flex;
  justify-content: flex-end;
  font-size: 12px;
  border-bottom: 1px solid #eee;
  .goods_num{
    margin-right: 14px;
  }
  .label{
    margin-right: 12px;
  }
  .price{
    font-size: 21px;
    color: #FA4E1B;
  }
}
.button{
  padding: 0 17px;
  height: 53px;
  line-height: 53px;
  display: flex;
  justify-content: flex-end;
  button{
    margin-top: 8px;
    margin-right: 12px;
  }
  .cancel{
    color: #999;
  }
  .payment{
    border: 1px solid #00BD71;
    border-radius: 2px;
    color: #00BD71;
  }
  button:last-child{
    margin-right: 0;
  }
}
.goods-list{
	background: #fff;
	padding: 0 17px;
  // margin-top: 13px;
	.goods-item{
		display: flex;
		border-bottom: 1px solid #eee;
		img{
			width: 80px;
			height: 80px;
		}
		.goods-content{
			margin-left: 12px;
			.goods-name{
				font-size: 13px;
			}
			.goods-desc{
				font-size: 12px;
				color: #999;
			}
			.goods-price{
				display: contents;
				font-size: 13px;
				span{
					display: inline-block;
					margin-left: 10px;
					font-size: 11px;
					color: #999;
				}
			}
		}
	}
	.goods-item:last-child{
		border-bottom: none;
	}
}
.yg-order {
  .order-content{
    .order-list {
      .item {
        background-color: #fff;
        margin-top: 0.25rem;
        .status {
          padding: 0.25rem 0.4rem;
          .status-left {
            color: #333;
            font-size: 14px;
            text-align: left;
            .van-icon {
              position: relative;
              top: 3px;
            }
          }
          .status-right {
            font-size: 13px;
            color: #00BD71;
            text-align: right;
          }
        }
        .van-card {
          margin-top: 0;
          background-color: #fff;
          .custom-card-footer {
            .order-info {
              font-size: 12px;
              color: #333;
              span {
                font-size: 21px;
                color: #FA4E1B;
              }
            }
            .edit {
              padding-top: 0.25rem;
              padding-bottom: 0.1rem;
              .logistics,
              .detailed,
              .cancel {
                font-size: 13px;
                color: #999;
                border: 1px solid #D8D8D8;
                border-radius: 4px;
              }
              .evaluation,
              .receipt,
              .payment,
              .remind {
                font-size: 13px;
                color: #00BD71;
                border: 1px solid #00BD71;
                border-radius: 4px;
              }
            }
          }
        }
      }
    }
  }
}
</style>

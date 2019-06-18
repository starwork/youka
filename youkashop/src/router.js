import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router);

const routes = [
  // {
  //   path: '*',
  //   redirect: '/home'
  // },
  {
    path: '/',
    redirect: '/home',
  },
  {
    path: '/home',
    component: () => import('@/views/home'),
    meta: {
      title: '悠咖首页',
			keepAlive:true
    }
  },
  {
    path: '/cart',
    component: () => import('@/views/cart'),
    meta: {
      title: '购物车',
			keepAlive:false
    }
  },
  {
    path: '/news',
    component: () => import('@/views/news'),
    redirect: '/news/notice',
    children: [
      {
        path: 'notice',
        name: 'notice',
        component: () => import('@/views/news/notice'),
        meta: {
          title: '消息',
					keepAlive:false
        }
      },
      {
        path: 'logistics',
        name: 'logistics',
        component: () => import('@/views/news/logistics'),
        meta: {
          title: '物流情况',
					keepAlive:true
        }
      }
    ]
  },
	{
	  path: '/comment',
	  component: () => import('@/views/item/comment'),
	  meta: {
	    title: '商品评论',
			keepAlive:true
	  }
	},
  {
    path: '/user',
    component: () => import('@/views/user'),
    redirect: '/user/center',
    children: [
      {
        path: 'center',
        name: 'center',
        component: () => import('@/views/user/center'),
        meta: {
          title: '个人中心',
					keepAlive:true
        }
      },
      {
        path: 'info',
        name: 'info',
        component: () => import('@/views/user/info'),
        meta: {
          title: '账户管理',
					keepAlive:true
        }
      },
      {
        path: 'address',
        name: 'address',
        component: () => import('@/views/user/address'),
        meta: {
          title: '我的收货地址',
					keepAlive:true
        }
      },
      {
        path: 'addressAdd',
        name: 'addressAdd',
        component: () => import('@/views/user/addressAdd'),
        meta: {
          title: '新增收货地址',
					keepAlive:true
        }
      },
      {
        path: 'bankCard',
        name: 'bankCard',
        component: () => import('@/views/user/bankCard'),
        meta: {
          title: '我的银行卡',
					keepAlive:true
        }
      },
      {
        path: 'bankCardAdd',
        name: 'bankCardAdd',
        component: () => import('@/views/user/bankCardAdd'),
        meta: {
          title: '新增银行卡',
					keepAlive:true
        }
      },
      {
        path: 'withdraw',
        name: 'withdraw',
        component: () => import('@/views/user/withdraw'),
        meta: {
          title: '提现',
					keepAlive:false
        }
      },
      {
        path: 'verified',
        name: 'verified',
        component: () => import('@/views/user/verified'),
        meta: {
          title: '身份验证',
					keepAlive:false
        }
      },
      {
        path: 'order',
        name: 'order',
        component: () => import('@/views/user/order'),
        meta: {
          title: '我的订单',
					keepAlive:true
        }
      },
      {
        path: 'evaluation',
        name: 'evaluation',
        component: () => import('@/views/user/evaluation'),
        meta: {
          title: '发布评价',
					keepAlive:false
        }
      },
      {
        path: 'userQR',
        name: 'userQR',
        component: () => import('@/views/user/userQR'),
        meta: {
          title: '我的二维码',
		  keepAlive:true
        }
      },
      {
        path: 'userInvite',
        name: 'userInvite',
        component: () => import('@/views/user/userInvite'),
        meta: {
          title: '邀请朋友',
			keepAlive:true
        }
      },
      {
        path: 'collection',
        name: 'collection',
        component: () => import('@/views/user/collection'),
        meta: {
          title: '我的收藏',
					keepAlive:true
        }
      },
      {
        path: 'statistics',
        name: 'statistics',
        component: () => import('@/views/user/statistics'),
        meta: {
          title: '数据统计',
					keepAlive:true
        }
      },
      {
        path: 'kanban',
        name: 'kanban',
        component: () => import('@/views/user/kanban'),
        meta: {
          title: '运营看板',
					keepAlive:true
        },
	},
	{
	  path: 'wealthManagement',
	  name: 'wealthManagement',
	  component: () => import('@/views/user/wealthManagement'),
	  meta: {
		title: '财富管理',
			keepAlive:true
	  }
	},
	{
        path: 'wealthIncome',
        name: 'wealthIncome',
        component: () => import('@/views/user/wealthIncome'),
        meta: {
          title: '奖励收入',
					keepAlive:true
        }
      },
	{
        path: 'wealthExpenditure',
        name: 'wealthExpenditure',
        component: () => import('@/views/user/wealthExpenditure'),
        meta: {
          title: '收支流水',
					keepAlive:true
        }
      },
	  {
	      path: 'wealthOutcome',
	      name: 'wealthOutcome',
	      component: () => import('@/views/user/wealthOutcome'),
	      meta: {
	        title: '奖励支出',
			keepAlive:true
	      }
	    },
	  {
        path: 'aloneAmount',
        name: 'aloneAmount',
        component: () => import('@/views/user/aloneAmount'),
        meta: {
          title: '单品数量',
					keepAlive:true
        }
      },
	{
        path: 'personManagement',
        name: 'personManagement',
        component: () => import('@/views/user/personManagement'),
        meta: {
          title: '人员管理',
					keepAlive:true
        }
      },
	  {
        path: 'teamManagement',
        name: 'teamManagement',
        component: () => import('@/views/user/teamManagement'),
        meta: {
          title: '团队管理',
		  keepAlive:true
        }
      },
	  {
	    path: 'teamAchieve',
	    name: 'teamAchieve',
	    component: () => import('@/views/user/teamAchieve'),
	    meta: {
	      title: '团队业绩',
	  		  keepAlive:true
	    }
	  },
	  {
	    path: 'myInvitation',
	    name: 'myInvitation',
	    component: () => import('@/views/user/myInvitation'),
	    meta: {
	      title: '我的邀请',
		  keepAlive:true
	    }
	  },
	  {
	    path: 'teamUserInfo',
	    name: 'teamUserInfo',
	    component: () => import('@/views/user/teamUserInfo'),
	    meta: {
	      title: '团队信息',
		  keepAlive:true
	    }
	  },
	  {
	    path: 'orderManagement',
	    name: 'orderManagement',
	    component: () => import('@/views/user/orderManagement'),
	    meta: {
	      title: '订货管理',
	  		  keepAlive:true
	    }
	  },
	  {
	    path: 'orderXiadan',
	    name: 'orderXiadan',
	    component: () => import('@/views/user/orderXiadan'),
	    meta: {
	      title: '订货下单',
	  		  keepAlive:true
	    }
	  },
	  {
	    path: 'suborder',
	    name: 'suborder',
	    component: () => import('@/views/user/suborder'),
	    meta: {
	      title: '下级订单',
	  		  keepAlive:true
	    }
	  },
	  {
	    path: 'callback',
	    name: 'callback',
	    component: () => import('@/views/user/callback'),
	    meta: {
	      title: '意见反馈',
	  		  keepAlive:true
	    }
	  },
    ]
  },
  {
    path: '/signin',
    component: () => import('@/views/signin'),
    meta: {
      title: '登录',
			keepAlive:false
    }
  },
  {
    path: '/signup',
    component: () => import('@/views/signup'),
    meta: {
      title: '注册',
			keepAlive:false
    }
  },
  {
    path: '/forget',
    component: () => import('@/views/forget'),
    meta: {
      title: '重置密码',
			keepAlive:false
    }
  },
  {
    path: '/list',
    component: () => import('@/views/list'),
    meta: {
      title: '商品列表',
			keepAlive:true
    }
  },
  {
    path: '/helper',
    component: () => import('@/views/helper'),
    meta: {
      title: '客服与帮助',
			keepAlive:true
    }
  },
  {
    path: '/protocol',
    component: () => import('@/views/protocol'),
    meta: {
      title: '隐私协议',
			keepAlive:true
    }
  },
	{
	  path: '/server',
	  component: () => import('@/views/protocol/server'),
	  meta: {
	    title: '服务协议',
			keepAlive:true
	  }
	},
  {
    path: '/item',
    component: () => import('@/views/item'),
    meta: {
      title: '商品详细页',
			keepAlive:true
    }
  },
	{
	  path: '/order/submit',
	  component: () => import('@/views/order/submit'),
	  meta: {
	    title: '订单提交',
			keepAlive:true
	  }
	},
	{
	  path: '/order/detail',
	  component: () => import('@/views/user/order_detail'),
	  meta: {
	    title: '订单详情',
			keepAlive:false
	  }
	},
	{
	  path: '/subscribe',
	  component: () => import('@/views/subscribe'),
	  meta: {
	    title: '关注公众号',
			keepAlive:false
	  }
	}
];

// add route path
routes.forEach(route => {
  route.path = route.path || '/' + (route.name || '');
});

const createRouter = () => new Router({
  mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  base: process.env.BASE_URL,
  routes: routes
});


const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher // reset router
}

router.beforeEach((to, from, next) => {
  const title = to.meta && to.meta.title;
  if (title) {
    document.title = title;
  }
  next();
});



export default router;

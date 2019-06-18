import request from '../utils/request';

/**
 * 行政地区
 * @param  level 层级 1 省份 2 城市 3 地区
 * @param  pid 上级ID
 * @returns {AxiosPromise}
 */
export function getAreaListApi(level, pid) {
  return request({
    url: '/address/area',
    method: 'get',
    params: {
      level: level,
      pid: pid,
    }
  })
}

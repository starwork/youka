import Cookies from 'js-cookie'

const TokenKey = 'yg_token';
const OPENID = 'yg_openid';

export function getToken() {
  return Cookies.get(TokenKey)
}

export function setToken(token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}

export function getOpenId()
{
  return Cookies.get(OPENID)
}

export function setOpenId(openid)
{
  return Cookies.set(OPENID,openid)
}

export function removeOpenId() {
  return Cookies.remove(OPENID)
}

export function getCode()
{
  return Cookies.get('invite_code')
}

export function setCode(code)
{
  return Cookies.set('invite_code',code)
}
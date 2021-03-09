/* 항상 최상위에 선언 */
import 'babel-polyfill'
import 'whatwg-fetch'
import fromEntries from 'object.fromentries'
import flatMap from 'array.prototype.flatmap'

if (!Object.fromEntries) {
  fromEntries.shim()
}
if (!Array.flatMap) {
  flatMap.shim()
}

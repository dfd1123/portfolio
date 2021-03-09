import _get from 'lodash/get'

const tables = {
  normalOrError (response) {
    if (response.status === 200 && _get(response, 'data.state') === 1) {
      return response
    } else {
      throw new Error(_get(response, 'data.msg', ''))
    }
  },
  resultOrError (response) {
    if (response.data.query) {
      return response.data.query
    } else {
      throw new Error(_get(response, 'data.msg', ''))
    }
  },
  alertIfMessage (response) {
    const msg = _get(response, 'data.msg', null)
    if (msg) {
      switch (_get(response, 'data.state')) {
        case 1:
          alert(msg)
          break
        default:
          alert(msg)
          break
      }
    }

    return response
  },
  updateCursor (data) {
    this.pagination.page = Number(data.page)
    this.pagination.count = Number(data.count)
    this.pagination.hasNext = this.pagination.page * this.pagination.limit <= this.pagination.count
    return data
  },
  moveCursor () {
    if (this.pagination.hasNext) {
      this.pagination.page = this.pagination.page + 1
      return true
    }
    return false
  },
  resetCursor () {
    this.pagination.page = 1
    this.pagination.hasNext = true
  }
}

export default tables

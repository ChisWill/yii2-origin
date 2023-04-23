// book.js
var util = require("../../utils/util");
var constant = require("../../utils/const");
import Toast from 'tdesign-miniprogram/toast/index';

Page({
  data: {
    phone: '',
    datetimeVisible: false,
    dateTimeValue: Date.now() - (new Date()).getSeconds() * 1000,
    dateTimeText: '',
    cart: [],
    parentList: [],
    itemList: []
  },

  onReady: function (options) {
    util.initUserData()
    var that = this
    wx.request({
      url: constant.default.REQUEST_URL + '/yu/item-list',
      data: {},
      success: function (res) {
        if (res.data.state == true) {
          that.parentList = res.data.info.parentList
          that.itemList = res.data.info.itemList
          that.setData({
            parentList: that.parentList,
            itemList: that.itemList
          })
        } else {
          Toast({
            context: that,
            selector: '#apiRes',
            message: '商品清单获取失败',
          });
        }
      }
    });
  },

  goTo: function (e) {
    util.goTo(e)
  },

  handlePhone(e) {
    this.setData({
      phone: e.detail.value
    })
  },

  showPicker(e) {
    this.setData({
      datetimeVisible: true,
    });
  },

  hidePicker() {
    this.setData({
      datetimeVisible: false,
    });
  },

  onConfirm(e) {
    const { value } = e?.detail;

    this.setData({
      dateTimeText: value,
    });

    this.hidePicker();
  },

  bookOrderRequest(cart) {
    var phone = this.data.phone
    var userData = wx.getStorageSync('userData')
    var openId = userData.openId
    var unionId = userData.unionId
    var arriveDate = this.data.dateTimeText

    var flag = false
    for (var k in cart) {
      if (parseInt(cart[k]) > 0) {
        flag = true
      }
    }

    if (!flag) {
      Toast({
        context: this,
        selector: '#apiRes',
        message: '请至少选择一个物品'
      })
      return
    }

    wx.request({
      url: constant.default.REQUEST_URL + '/yu/book-goods',
      method: 'POST',
      data: {
        openId: openId,
        unionId: unionId,
        phone: phone,
        cart: cart,
        arriveDate: arriveDate
      },
      success: function (res) {
        if (res.data.state == true) {
          Toast({
            context: this,
            selector: '#apiRes',
            message: '预定成功'
          })
          setTimeout(() => {
            wx.redirectTo({
              url: '../order/order',
            })
          }, 500);
        } else {
          Toast({
            context: this,
            selector: '#apiRes',
            message: res.data.info
          })
        }
      }
    })
  },

  bookOrder: function (e) {
    if (this.data.phone == '') {
      Toast({
        context: this,
        selector: '#apiRes',
        message: '请填写手机号后四位'
      })
      return
    }
    // if (this.data.phone[0] != '1' || this.data.phone.length != 11) {
    //   Toast({
    //     context: this,
    //     selector: '#apiRes',
    //     message: '手机号格式不正确'
    //   })
    //   return
    // }

    if (this.data.dateTimeText == '') {
      Toast({
        context: this,
        selector: '#apiRes',
        message: '请选择期望到货时间'
      })
      return
    }

    this.bookOrderRequest(e.detail.value)
  }
})

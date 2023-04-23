// pages/order.js

var util = require("../../utils/util");
var constant = require("../../utils/const");

Page({

  /**
   * 页面的初始数据
   */
  data: {
    orderList: []
  },

  goTo: function (e) {
    util.goTo(e)
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
    util.initUserData()

    this.showOrderList()
  },

  showOrderList() {
    var userData = wx.getStorageSync('userData')
    var that = this
    wx.request({
      url: constant.default.REQUEST_URL + '/yu/order-list',
      data: {
        openId: userData.openId 
      },
      success: function(res) {
        if (res.data.state == true) {
          that.setData({
            orderList: res.data.info
          })
        }
      }
    })
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})
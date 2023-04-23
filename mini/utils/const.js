var env = 'prod';
var constant = {
    REQUEST_URL: 'https://yy.chiswill.cc'
};
if (env === 'develop') {
    constant.REQUEST_URL = 'http://origin.cc';
}
exports.default = constant;
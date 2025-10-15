<template>
  <el-card class="version-information" shadow="hover">
    <template #header>
      <svg-icon icon-class="component" />
      <span class="margin-left-xs">服务套餐</span>
    </template>
    <el-scrollbar>
      <table class="table text-center">
        <tr>
          <td>店滴CMS(php)</td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>店滴数据处理中心(数据接口)</td>
          <td> <i class="el-icon-close" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>中后台(element-admin)</td>
          <td> <i class="el-icon-close" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>免费插件</td>
          <td> <i class="el-icon-close" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>单运营主体支持</td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>多运营主体支持</td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>一对多技术支持</td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>一对一技术支持</td>
          <td> <i class="el-icon-close" /></td>
          <td> <i class="el-icon-check" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>个性定制</td>
          <td> <i class="el-icon-close" /></td>
          <td> <i class="el-icon-close" /></td>
          <td> <i class="el-icon-check" /></td>
        </tr>
        <tr>
          <td>
            套餐购买
          </td>
          <td class="text-center">
            <a
              href="https://gitee.com/tuhuokeji/diandcms"
              target="_blank"
            >
              <el-button type="primary">
                开源免费版
              </el-button>
            </a>

          </td>
          <td colspan="1" class="text-center">
            <a
              target="_blank"
              href="https://www.hopesfire.com/portal.php?fid=64"
            >
              <el-button type="warning">
                商业授权版
              </el-button>
            </a>
          </td>
          <td colspan="1" class="text-center">
            <a
              target="_blank"
              href="https://www.hopesfire.com/forum.php?mod=forumdisplay&fid=45"
            >
              <el-button type="danger">
                企业定制版
              </el-button>
            </a>

          </td>
        </tr>
      </table>
    </el-scrollbar>
  </el-card>
</template>

<script>
export default {
  name: 'VersionInformation',
  props: {
    dependencies: {
      type: Object,
      default: function() {
        return {}
      }
    },
    devDependencies: {
      type: Object,
      default: function() {
        return {}
      }
    }
  },
  data() {
    return {
    }
  }
}
</script>

<style lang="scss" scoped>
  .version-information {
    .table {
      width: 100%;
      color: #666;
      border-collapse: collapse;
      background-color: #fff;

      td {
        position: relative;
        padding: 9px 15px;
        overflow: hidden;
        font-size: 14px;
        line-height: 20px;
        text-overflow: ellipsis;
        white-space: nowrap;
        border: 1px solid #e6e6e6;
        .el-icon-check{
              color: #1890ff;
              font-weight: 600;
              font-size: 16px;
        }
        .el-icon-close{
              color: #ff4949;
              font-weight: 600;
              font-size: 16px;
        }
        &:nth-child(odd) {
          width: 20%;
          text-align: right;
          background-color: #f7f7f7;
        }
      }
    }
  }
</style>

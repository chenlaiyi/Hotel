<template>
  <div class="login">
    <form class="login__card">
      <h1>酒店后台登录</h1>
      <p>此页面用于演示 SSR 项目中的受保护路由。设置 cookie <code>hotel-auth=1</code> 即视为已登录。</p>
      <label>
        账号
        <input type="text" placeholder="admin" disabled />
      </label>
      <label>
        密码
        <input type="password" placeholder="∙∙∙∙∙∙" disabled />
      </label>
      <small>登录逻辑由店滴云 SSO 或自建鉴权实现。</small>
    </form>
  </div>
</template>

<script setup lang="ts">
defineOptions({ name: 'LoginPage' });
</script>

<style scoped>
.login {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(120deg, rgba(15,81,50,0.85), rgba(5,38,24,0.72));
  color: #fff;
  padding: 32px;
}
.login__card {
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(10px);
  border-radius: 16px;
  padding: 32px;
  width: min(360px, 100%);
  display: flex;
  flex-direction: column;
  gap: 16px;
}
input {
  width: 100%;
  padding: 10px 12px;
  border-radius: 8px;
  border: none;
  margin-top: 6px;
}
small {
  opacity: 0.8;
}
</style>

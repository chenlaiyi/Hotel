<?php

echo '<?php';
?>

<div id="install-container">
    <h2>店滴云安装向导</h2>
    <div class="step-bar">
        <div id="steptab1" class="steptab active">第一步：配置数据库</div>
        <div id="steptab2" class="steptab">第二步：配置管理员信息</div>
        <div id="steptab3" class="steptab">第三步：安装完成并访问</div>
    </div>
    <div id="step1" class="step">
        <h3>数据库配置</h3>
        <div class="form-group">
            <label for="host">ip地址</label>
            <input type="text" id="host" value="127.0.0.1">
        </div>
        <div class="form-group">
            <label for="port">数据库端口</label>
            <input type="text" id="port" value="3306">
        </div>
        <div class="form-group">
            <label for="dbname">数据库名称</label>
            <input type="text" id="dbname">
        </div>
        <div class="form-group">
            <label for="tablePrefix">数据库前缀</label>
            <input type="text" id="tablePrefix" value="dd_">
        </div>
        <div class="form-group">
            <label for="dbusername">用户名</label>
            <input type="text" id="dbusername">
        </div>
        <div class="form-group">
            <label for="dbpassword">密码</label>
            <input type="password" id="dbpassword">
        </div>
        <div class="form-group">
            <button onclick="submitStep1()" id="step-btn-1">下一步</button>
        </div>
        <div class="message" id="step1-message"></div>
    </div>
    <div id="step2" class="step" style="display: none;">
        <h3>管理员注册</h3>
        <div class="form-group">
            <label for="username">用户名</label>
            <input type="text" id="username">
        </div>
        <div class="form-group">
            <label for="mobile">手机号</label>
            <input type="text" id="mobile">
        </div>
        <div class="form-group">
            <label for="email">邮箱</label>
            <input type="email" id="email">
        </div>
        <div class="form-group">
            <label for="userpassword">密码</label>
            <input type="password" id="userpassword">
        </div>
        <div class="form-group form-group-flex">
            <button onclick="goStep1()">上一步</button>

            <button onclick="submitStep2()">注册管理员</button>
        </div>
        <div class="message" id="step2-message"></div>
    </div>
    <div id="step3" class="step" style="display: none;">
        <h3>安装成功</h3>
        <div class="message" id="step3-message"></div>
        <div class="form-group">
            测试接口地址：<a href="http://www.ddiot.com/api/ceshi/index">http://www.ddiot.com/api/ceshi/index</a>
        </div>
        <div class="form-group">
            代码生成工具：<a href="http://www.ddiot.com/develop">http://www.ddiot.com/develop</a>
        </div>
        <div class="form-group">
            后台登录地址：<a href="http://www.ddiot.com/backend">http://www.ddiot.com/backend</a>
        </div>
        <div class="loading-indicator" id="loading-indicator" style="display: none;">测试地接地址</div>
    </div>
</div>

<script>
    async function submitStep1() {
        const host = document.getElementById('host').value;
        const port = document.getElementById('port').value;
        const dbname = document.getElementById('dbname').value;
        const tablePrefix = document.getElementById('tablePrefix').value;
        const dbusername = document.getElementById('dbusername').value;
        const dbpassword = document.getElementById('dbpassword').value;

        const response = await fetch('/install/index.php?r=site/step1', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body:  new URLSearchParams({
                host,
                port,
                dbname,
                tablePrefix,
                dbusername,
                dbpassword
            }).toString()
        });

        const result = await response.json();
        const messageElement = document.getElementById('step1-message');
        if (result.success) {
            // step-btn-1
            document.getElementById('step-btn-1').textContent = '正在安装数据库...';
            submitInitSql()
        } else {
            messageElement.style.color = 'red';
            messageElement.textContent = result.message;
        }
    }



    async function submitInitSql() {
        const host = document.getElementById('host').value;
        const port = document.getElementById('port').value;
        const dbname = document.getElementById('dbname').value;
        const tablePrefix = document.getElementById('tablePrefix').value;
        const dbusername = document.getElementById('dbusername').value;
        const dbpassword = document.getElementById('dbpassword').value;
        // 显示加载指示器
        document.getElementById('loading-indicator').style.display = 'block';

        const response = await fetch('/install/index.php?r=site/init-sql', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                host,
                port,
                dbname,
                tablePrefix,
                dbusername,
                dbpassword
            }).toString()
        });
        const result = await response.json();

        console.log(result);
        // 显示加载指示器
        document.getElementById('loading-indicator').style.display = 'none';

        const messageElement = document.getElementById('step2-message');
        if (result.success) {

            document.getElementById('step-btn-1').textContent = '数据库配置成功，下一步注册管理员';
            messageElement.style.color = 'green';
            messageElement.textContent = '数据库配置成功，下一步注册管理员';
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'block';
            // steptab1
            document.getElementById('steptab1').classList.remove('active');
            document.getElementById('steptab2').classList.add('active');
        } else {
            messageElement.style.color = 'red';
            messageElement.textContent = result.message;
        }
    }


    async function submitStep2() {
        const username = document.getElementById('username').value;
        const mobile = document.getElementById('mobile').value;
        const email = document.getElementById('email').value;
        const userpassword = document.getElementById('userpassword').value;

        const response = await fetch('/install/index.php?r=site/step2', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                username,
                mobile,
                email,
                userpassword
            }).toString()
        });

        const result = await response.json();
        const messageElement = document.getElementById('step2-message');
        if (result.success) {
            messageElement.style.color = 'green';
            messageElement.textContent = '系统安装成功，配置你的nginx就可以访问了';
            // 你可以在这里添加重定向或其他操作
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step3').style.display = 'block';

            document.getElementById('steptab2').classList.remove('active');
            document.getElementById('steptab3').classList.add('active');
        } else {
            messageElement.style.color = 'red';
            messageElement.textContent = result.message;
        }
    }

    // goStep1
    function goStep1() {
        document.getElementById('step1').style.display = 'block';
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step3').style.display = 'none';
        document.getElementById('steptab1').classList.add('active');
        document.getElementById('steptab2').classList.remove('active');
        document.getElementById('steptab3').classList.remove('active');
    }

    function goStep2() {
        document.getElementById('step1').style.display = 'none';
        document.getElementById('step2').style.display = 'block';
        document.getElementById('steptab1').classList.remove('active');
        document.getElementById('steptab2').classList.add('active');
    }

    function goStep3() {
        document.getElementById('step2').style.display = 'none';
        document.getElementById('step3').style.display = 'block';
        document.getElementById('steptab2').classList.remove('active');
        document.getElementById('steptab3').classList.add('active');
    }
</script>
<div id="install-container">
    <h2>店滴云安装向导</h2>
    <div id="step1" class="step">
        <div class="content" id="content">
            <!-- 环境检查部分 -->
            <div class="pr-title">
                <h3>安装环境检查</h3>
            </div>
            <div class="check-box bgf">
                <table id="check-env-table">
                    <thead>
                    <tr>
                        <th style="width: 35%;">项目名称</th>
                        <th style="width: 35%;">所需环境</th>
                        <th style="width: 30%;">当前环境</th>
                    </tr>
                    </thead>
                    <tbody id="check-env-body">
                    </tbody>
                </table>
            </div>

            <!-- 文件和文件夹权限检查部分 -->
            <div class="pr-title mt10">
                <h3>文件和文件夹权限检查</h3>
            </div>
            <div class="check-box bgf">
                <table id="check-file-table">
                    <thead>
                    <tr>
                        <th style="width: 35%;">文件/文件夹</th>
                        <th style="width: 35%;">所需权限</th>
                        <th style="width: 30%;">当前权限</th>
                    </tr>
                    </thead>
                    <tbody id="check-file-body">
                    </tbody>
                </table>
            </div>

            <!-- 消息部分 -->
            <ul class="messages" id="messages-list">
            </ul>

        </div>

        <div class="form-group">
            <a href="/install/index.php?r=site/install">
                <button>开始安装</button>
            </a>
        </div>
        <div class="message" id="step1-message"></div>
    </div>
</div>

<script>
    /**
     * 初始化执行
     * @returns {Promise<void>}
     */
    async function submitStep1() {
        const response = await fetch('/install/index.php?r=site/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
            }).toString()
        });

        const result = await response.json();
        const messageElement = document.getElementById('step1-message');
        if (result.success) {
            const data = result.data;

            // 环境检查部分
            const checkEnvBody = document.getElementById('check-env-body');
            Object.keys(data.check_env.detail).forEach((ek) => {
                const ev = data.check_env.detail[ek];
                const row = document.createElement('tr');
                const itemNameCell = document.createElement('td');
                itemNameCell.innerText = ek;
                const requiredEnvCell = document.createElement('td');
                requiredEnvCell.innerText = ev.required;
                const currentServerCell = document.createElement('td');
                const icoSpan = document.createElement('span');
                icoSpan.className = `ico ${ev.result}`;
                const currentSpan = document.createElement('span');
                currentSpan.innerText = ev.current;
                currentServerCell.appendChild(icoSpan);
                currentServerCell.appendChild(currentSpan);

                row.appendChild(itemNameCell);
                row.appendChild(requiredEnvCell);
                row.appendChild(currentServerCell);
                checkEnvBody.appendChild(row);
            });

            // 文件和文件夹权限检查部分
            const checkFileBody = document.getElementById('check-file-body');
            data.check_file.detail.forEach((ev) => {
                const row = document.createElement('tr');
                const fileCell = document.createElement('td');
                fileCell.innerText = ev.file;
                const requiredPrivCell = document.createElement('td');
                requiredPrivCell.innerText = '可写'; // 假设所有文件都需要可写权限
                const currentPrivCell = document.createElement('td');
                const icoSpan = document.createElement('span');
                icoSpan.className = `ico ${ev.result}`;
                const currentSpan = document.createElement('span');
                currentSpan.innerText = ev.current;
                currentPrivCell.appendChild(icoSpan);
                currentPrivCell.appendChild(currentSpan);

                row.appendChild(fileCell);
                row.appendChild(requiredPrivCell);
                row.appendChild(currentPrivCell);
                checkFileBody.appendChild(row);
            });

            // 消息部分
            const messagesList = document.getElementById('messages-list');
            data.messages.forEach((msg) => {
                const li = document.createElement('li');
                li.innerText = msg;
                messagesList.appendChild(li);
            });

            // 隐藏字段部分
            const postForm = document.getElementById('post_form');
            Object.keys(data.hiddens).forEach((hk) => {
                const hv = data.hiddens[hk];
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = hk;
                input.value = hv;
                postForm.appendChild(input);
            });

            // 兼容性检查
            const compatibleInput = document.querySelector('input[name="compatible"]');
            compatibleInput.value = data.compatible ? 'true' : 'false';

            // 按钮部分
            const submitButton = document.getElementById('submit_button');
            submitButton.value = 'Next';
            submitButton.onclick = function() {
                postForm.action = '/install/index.php?r=default/config'; // 假设这是正确的 URL
                postForm.submit();
            };

            const recheckButton = document.querySelector('input[value="Recheck"]');
            if (!data.compatible) {
                recheckButton.style.display = 'inline-block';
            } else {
                recheckButton.style.display = 'none';
            }

        } else {
            messageElement.style.color = 'red';
            messageElement.textContent = result.message;
        }
    }

    // 初始化调用
    document.addEventListener('DOMContentLoaded', () => {
        submitStep1();
    });
</script>

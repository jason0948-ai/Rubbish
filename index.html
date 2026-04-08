<?php
// 延長 PHP 執行時間至 120 秒
set_time_limit(120);

// 定義 API Key 和 Endpoint
$apiKey = "sk-AG0EJbAgjvM7yTLfZclzSRhcBjyYorY5eQpjGvLOe5O1zD1U"; 

// 設定為您的第三方代理伺服器 API 路徑 (不帶 key 參數)
$apiUrl = "https://yinli.one/v1beta/models/gemini-2.5-flash:generateContent";

// 檢查是否為 POST 請求（接收前端傳來的 JSON + Base64 圖片）
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 設定回傳格式為 JSON
    header('Content-Type: application/json');
    
    // 解析前端傳來的 JSON 資料
    $inputData = json_decode(file_get_contents('php://input'), true);
    $base64Url = isset($inputData['image']) ? $inputData['image'] : '';

    if (empty($base64Url)) {
        echo json_encode(['success' => false, 'message' => '未收到影像資料。']);
        exit;
    }

    // 處理 Base64 圖片資料：Gemini API 只接受純 Base64 字串，不包含 'data:image/jpeg;base64,' 前綴
    $mimeType = 'image/jpeg'; // 預設 MIME 類型
    $base64Data = $base64Url;

    if (strpos($base64Url, 'data:') === 0) {
        $parts = explode(',', $base64Url);
        $base64Data = $parts[1];
        // 取得正確的 MIME 類型
        if (preg_match('/^data:(.*?);base64/i', $parts[0], $matches)) {
            $mimeType = $matches[1];
        }
    }

    // 構建傳送給 Gemini API 的專屬資料載荷 (Payload)
    $data = [
        "systemInstruction" => [
            "parts" => [
                [
                    "text" => "你是一個專業的垃圾分類助手。請將用戶圖片中的主要物品分類為以下三類之一：【可焚燒】、【可回收】、【不可回收】。請務必只輸出這三個詞彙中的一個，不要包含任何其他標點符號或多餘的解釋。"
                ]
            ]
        ],
        "contents" => [
            [
                "role" => "user",
                "parts" => [
                    [
                        "inlineData" => [
                            "mimeType" => $mimeType,
                            "data" => $base64Data
                        ]
                    ],
                    [
                        "text" => "請識別這張圖片中的主要物品，並告訴我它屬於哪一類垃圾。"
                    ]
                ]
            ]
        ],
        "generationConfig" => [
            "temperature" => 0.1
        ]
    ];

    // 初始化 cURL
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 忽略 SSL 驗證以配合代理

    // 執行 cURL 請求
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // 錯誤處理與解析結果
    if (curl_errno($ch)) {
        echo json_encode(['success' => false, 'message' => "連線錯誤: " . curl_error($ch)]);
    } else if ($httpCode != 200) {
        // 嘗試從錯誤回應中萃取詳細訊息
        $errData = json_decode($response, true);
        $errMsg = isset($errData['error']['message']) ? $errData['error']['message'] : strip_tags(mb_substr($response, 0, 150));
        echo json_encode(['success' => false, 'message' => "API 錯誤 (" . $httpCode . "): " . $errMsg]);
    } else {
        $responseData = json_decode($response, true);
        // 讀取 Gemini 專屬的回應結構
        if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            $result = trim($responseData['candidates'][0]['content']['parts'][0]['text']);
            echo json_encode(['success' => true, 'result' => $result]);
        } else {
            echo json_encode(['success' => false, 'message' => "無法解析 API 回應結構。"]);
        }
    }
    curl_close($ch);
    exit; // 終止腳本，不要輸出後面的 HTML
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自動安檢與轉廢為能系統</title>
    <!-- 使用 Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #070B14; }
        .tech-card { background-color: #111827; border-color: #1F2937; }
        .tech-text { color: #00E5FF; text-shadow: 0 0 10px rgba(0, 229, 255, 0.4); }
        .dotted-line { overflow: hidden; white-space: nowrap; color: #fff; letter-spacing: 2px; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 font-sans text-gray-200">

    <div class="tech-card rounded-xl shadow-2xl p-6 w-full max-w-lg border">
        
        <!-- 標題區塊 -->
        <h1 class="text-xl font-bold text-center mb-4 tech-text tracking-widest">自動安檢與轉廢為能系統</h1>

        <!-- 虛線裝飾框 -->
        <div class="bg-gray-900 border border-gray-700 rounded p-1 mb-4 dotted-line text-xs opacity-50">
            ......................................................................
        </div>

        <!-- 影像畫面容器 -->
        <div class="bg-black rounded-lg border border-gray-700 overflow-hidden relative aspect-[4/3] flex items-center justify-center">
            <video id="cameraStream" autoplay playsinline class="w-full h-full object-cover hidden"></video>
            <!-- 尚未連線時的替代黑畫面 -->
            <div id="cameraPlaceholder" class="absolute inset-0 flex items-center justify-center text-gray-600 text-sm">
                載入相機中...
            </div>
        </div>

        <!-- 按鈕區塊 -->
        <button id="scanBtn" class="mt-4 w-full bg-cyan-700 hover:bg-cyan-600 disabled:bg-gray-700 disabled:text-gray-400 text-white font-bold py-3 rounded text-sm tracking-wider transition duration-200 shadow-[0_0_15px_rgba(0,188,212,0.3)] disabled:shadow-none" disabled>
            [ 啟動安檢分析 ]
        </button>

        <!-- 系統狀態列 -->
        <div id="statusBar" class="mt-4 bg-gray-800 border border-gray-700 text-gray-400 text-sm py-2 px-3 rounded text-center tracking-wide flex items-center justify-center gap-2">
            <span id="statusIcon">🔄</span> 
            <span id="statusText">系統校準中...</span>
        </div>

        <!-- 錯誤與日誌輸出區塊 -->
        <div id="logArea" class="mt-4 bg-[#0F172A] border border-[#1E293B] rounded p-4 text-sm font-mono min-h-[80px]">
            <div id="logContent" class="text-gray-400">系統初始化...</div>
        </div>
        
        <!-- 用於截圖的隱藏畫布 -->
        <canvas id="captureCanvas" class="hidden"></canvas>
    </div>

    <script>
        const video = document.getElementById('cameraStream');
        const placeholder = document.getElementById('cameraPlaceholder');
        const canvas = document.getElementById('captureCanvas');
        const scanBtn = document.getElementById('scanBtn');
        const statusIcon = document.getElementById('statusIcon');
        const statusText = document.getElementById('statusText');
        const logContent = document.getElementById('logContent');

        let streamActive = false;

        // 初始化相機
        async function initCamera() {
            try {
                // 請求後置鏡頭
                const stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: "environment" } 
                });
                
                video.srcObject = stream;
                video.classList.remove('hidden');
                placeholder.classList.add('hidden');
                
                // 相機準備就緒
                video.onloadedmetadata = () => {
                    streamActive = true;
                    scanBtn.disabled = false;
                    statusIcon.textContent = "✅";
                    statusText.textContent = "相機已連線，系統就緒";
                    statusText.className = "text-green-400";
                    logContent.innerHTML = "<span class='text-green-400'>[系統] 權限已核准，準備執行安檢掃描。</span>";
                };

            } catch (err) {
                console.error("相機存取失敗:", err);
                statusIcon.textContent = "⚠️";
                statusText.textContent = "系統異常";
                statusText.className = "text-red-400";
                logContent.innerHTML = "❌ <span class='text-red-400'>無法存取相機，請檢查權限。</span><br><span class='text-gray-500 text-xs mt-2 block'>(提示: 瀏覽器需在 HTTPS 環境下才能開啟相機)</span>";
            }
        }

        // 啟動分析流程
        scanBtn.addEventListener('click', async () => {
            if (!streamActive) return;

            // 鎖定按鈕，防止重複點擊
            scanBtn.disabled = true;
            statusIcon.className = "animate-spin inline-block";
            statusIcon.textContent = "🔄";
            statusText.textContent = "影像分析中...";
            statusText.className = "text-cyan-400";
            logContent.innerHTML = "<span class='text-cyan-400'>[系統] 正在擷取當前影像...</span>";

            // 1. 從影片截取圖片畫到 Canvas
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            // 2. 將 Canvas 轉為 Base64 (設定品質 0.8 壓縮以防檔案過大)
            const base64Data = canvas.toDataURL('image/jpeg', 0.8);
            
            logContent.innerHTML += "<br><span class='text-cyan-400'>[系統] 上傳資料至 AI 核心模組...</span>";

            try {
                // 3. 透過 AJAX 發送給同一個 PHP 檔案處理
                const response = await fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ image: base64Data })
                });

                const data = await response.json();

                // 4. 處理回應結果
                statusIcon.className = ""; // 移除旋轉動畫
                if (data.success) {
                    statusIcon.textContent = "✅";
                    statusText.textContent = "分析完成";
                    statusText.className = "text-green-400";
                    
                    // 根據結果給予不同顏色的提示
                    let resultColor = "text-gray-300";
                    if (data.result.includes('可回收') && !data.result.includes('不可')) resultColor = "text-blue-400";
                    else if (data.result.includes('可焚燒')) resultColor = "text-orange-400";
                    else if (data.result.includes('不可回收')) resultColor = "text-red-500";

                    logContent.innerHTML = `<span class="text-gray-400">[判定結果]</span><br><span class="text-2xl font-bold ${resultColor}">${data.result}</span>`;
                } else {
                    statusIcon.textContent = "❌";
                    statusText.textContent = "分析失敗";
                    statusText.className = "text-red-400";
                    logContent.innerHTML = `<span class='text-red-400'>[錯誤] ${data.message}</span>`;
                }
            } catch (err) {
                statusIcon.className = "";
                statusIcon.textContent = "❌";
                statusText.textContent = "網路錯誤";
                statusText.className = "text-red-400";
                logContent.innerHTML = `<span class='text-red-400'>[系統] 連線異常，請稍後再試。</span>`;
            } finally {
                // 恢復按鈕狀態
                scanBtn.disabled = false;
            }
        });

        // 網頁載入後立即要求開啟相機
        window.addEventListener('DOMContentLoaded', initCamera);
    </script>
</body>
</html>
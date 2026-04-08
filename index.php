```php
<?php
// ==========================================
// 後端 PHP 處理區塊 (處理 AJAX API 請求)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'classify') {
    // 設定回傳為 JSON 格式
    header('Content-Type: application/json');

    // 獲取前端傳來的 JSON payload
    $input = json_decode(file_get_contents('php://input'), true);
    $base64Image = $input['image'] ?? null;

    if (!$base64Image) {
        echo json_encode(['error' => '請提供圖片資料']);
        exit;
    }

    // 您提供的 API 資訊
    $apiUrl = 'https://yinli.one/v1/chat/completions';
    $apiKey = 'sk-AG0EJbAgjvM7yTLfZclzSRhcBjyYorY5eQpjGvLOe5O1zD1U';

    // 準備傳送給 Gemini 2.5 Pro 的 Payload (OpenAI 視覺格式)
    $data = [
        'model' => 'gemini-2.5-pro',
        'messages' => [
            [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text', 
                        'text' => '請分辨圖片中的垃圾分類（例如：塑膠、紙類、金屬、玻璃、廚餘、一般垃圾等），並給出簡短的處理與回收建議。'
                    ],
                    [
                        'type' => 'image_url', 
                        'image_url' => [
                            'url' => $base64Image
                        ]
                    ]
                ]
            ]
        ],
        'max_tokens' => 800
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

    // 執行請求
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    // 回傳結果給前端
    if ($error) {
        echo json_encode(['error' => 'cURL 請求錯誤: ' . $error]);
    } else {
        echo $response;
    }
    exit; // 確保不會輸出下方的 HTML
}
?>
<!DOCTYPE html>
<html lang="zh-HK">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>智淨城市 AI 分類掃描</title>
    <!-- 引入 Tailwind CSS 進行快速排版 -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* 隱藏捲軸但保持可捲動 */
        ::-webkit-scrollbar { width: 0px; background: transparent; }
        body { 
            background-color: #050505; /* 黑色背景 */
            color: #3b82f6; /* 預設藍色字體 */
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center py-8 px-4">

    <!-- 標題區塊 -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold tracking-wider mb-2 text-blue-500">智淨城市 AI 分類掃描</h1>
        <p class="text-sm text-blue-300 opacity-80">請掃描廢物，或使用「上傳相片」功能測試！</p>
    </div>

    <!-- 影像掃描/預覽區塊 -->
    <div class="relative w-full max-w-sm aspect-[3/4] bg-gray-900 rounded-2xl border-2 border-blue-600 shadow-[0_0_15px_rgba(59,130,246,0.3)] overflow-hidden mb-8 flex items-center justify-center">
        
        <!-- 攝影機畫面 -->
        <video id="camera-feed" class="absolute inset-0 w-full h-full object-cover" autoplay playsinline></video>
        
        <!-- 截取的影像或上傳的影像預覽 -->
        <img id="image-preview" class="absolute inset-0 w-full h-full object-cover hidden" alt="預覽影像">
        
        <!-- 隱藏的 Canvas 用於處理影像 -->
        <canvas id="capture-canvas" class="hidden"></canvas>

        <!-- 掃描動畫線 (裝飾用) -->
        <div id="scan-line" class="absolute top-0 left-0 w-full h-1 bg-blue-400 shadow-[0_0_10px_#60a5fa] hidden animate-[scan_2s_ease-in-out_infinite]"></div>
    </div>

    <!-- 操作按鈕區塊 -->
    <div class="flex flex-col gap-4 w-full max-w-sm">
        <button id="btn-scan" class="flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-700 border border-blue-500 text-blue-400 font-bold py-3 px-6 rounded-xl transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path><circle cx="12" cy="13" r="3"></circle></svg>
            掃描相機畫面
        </button>

        <button id="btn-upload" class="flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-700 border border-blue-500 text-blue-400 font-bold py-3 px-6 rounded-xl transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
            上傳垃圾相片
        </button>
        <!-- 隱藏的檔案上傳元件 -->
        <input type="file" id="file-input" accept="image/*" class="hidden">
    </div>

    <!-- AI 分析結果顯示區塊 -->
    <div id="result-container" class="w-full max-w-sm mt-8 p-5 bg-gray-900 border border-blue-800 rounded-xl hidden">
        <h3 class="text-lg font-bold text-blue-400 mb-2 border-b border-blue-800 pb-2">AI 分類結果</h3>
        <div id="result-text" class="text-blue-100 text-sm leading-relaxed whitespace-pre-wrap">
            <!-- 結果會顯示在這裡 -->
        </div>
    </div>

    <!-- Tailwind 擴展自訂動畫 -->
    <style>
        @keyframes scan {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.getElementById('camera-feed');
            const canvas = document.getElementById('capture-canvas');
            const imagePreview = document.getElementById('image-preview');
            const btnScan = document.getElementById('btn-scan');
            const btnUpload = document.getElementById('btn-upload');
            const fileInput = document.getElementById('file-input');
            const resultContainer = document.getElementById('result-container');
            const resultText = document.getElementById('result-text');
            const scanLine = document.getElementById('scan-line');

            let stream = null;

            // 啟動相機
            async function startCamera() {
                try {
                    stream = await navigator.mediaDevices.getUserMedia({ 
                        video: { facingMode: 'environment' } // 優先使用後置鏡頭
                    });
                    video.srcObject = stream;
                    video.classList.remove('hidden');
                    imagePreview.classList.add('hidden');
                } catch (err) {
                    console.error("無法存取相機: ", err);
                    // 如果無法存取相機，仍然可以使用上傳功能
                }
            }

            // 初始化時啟動相機
            startCamera();

            // 處理「掃描相機畫面」按鈕點擊
            btnScan.addEventListener('click', () => {
                if (!stream) {
                    alert("相機未啟用，請檢查權限或使用上傳功能。");
                    return;
                }

                // 設定 Canvas 尺寸與影片相同
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                // 轉換為 Base64 (壓縮品質為 0.7 以減少傳輸量)
                const base64Image = canvas.toDataURL('image/jpeg', 0.7);
                
                // 顯示預覽畫面，隱藏實時影片
                imagePreview.src = base64Image;
                imagePreview.classList.remove('hidden');
                video.classList.add('hidden');

                // 發送給 AI 進行分析
                analyzeImage(base64Image);
            });

            // 處理「上傳垃圾相片」按鈕點擊
            btnUpload.addEventListener('click', () => {
                fileInput.click();
            });

            // 處理檔案選擇
            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (event) => {
                    const base64Image = event.target.result;
                    
                    // 顯示預覽
                    imagePreview.src = base64Image;
                    imagePreview.classList.remove('hidden');
                    video.classList.add('hidden');

                    // 停止相機以節省資源
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                    }

                    // 發送給 AI 進行分析
                    analyzeImage(base64Image);
                };
                reader.readAsDataURL(file);
            });

            // 呼叫 PHP 後端 API 進行 AI 分析
            async function analyzeImage(base64Image) {
                // UI 狀態更新：顯示載入中
                btnScan.disabled = true;
                btnUpload.disabled = true;
                scanLine.classList.remove('hidden');
                resultContainer.classList.remove('hidden');
                resultText.innerHTML = '<span class="animate-pulse text-blue-500">AI 正在努力分析中，請稍候...</span>';

                try {
                    // 發送 POST 請求給當前頁面的 PHP 邏輯
                    const response = await fetch('?action=classify', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ image: base64Image })
                    });

                    const data = await response.json();

                    if (data.error) {
                        throw new Error(data.error);
                    }

                    // 解析 OpenAI/Gemini 相容格式的 API 回傳值
                    let aiResponse = "無法解析結果";
                    if (data.choices && data.choices[0] && data.choices[0].message) {
                        aiResponse = data.choices[0].message.content;
                    }

                    // 顯示結果
                    resultText.innerHTML = aiResponse;

                } catch (error) {
                    console.error('Error:', error);
                    resultText.innerHTML = `<span class="text-red-400">發生錯誤：${error.message}</span>`;
                } finally {
                    // 恢復 UI 狀態
                    btnScan.disabled = false;
                    btnUpload.disabled = false;
                    scanLine.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>

```

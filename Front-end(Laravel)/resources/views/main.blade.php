<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        @vite('resources/css/app.css')
    </head>
    <body>
        <div class="m-2 max-w-[880px] mx-auto">
            <div class="mx-6">
                <div class="flex justify-center w-full text-cyan-500 font-semibold h-18 bg-gray-900 text-xl">
                    <span class="m-6">รางวัลล็อตเตอรี่ Diversition</span>
                </div>
                <div class="mt-6">
                    <div class="text-lg font-semibold">ผลการออกรางวัลล็อตเตอรี่</div>
                    <button id="randomizeButton" class="mt-1 p-2 border-2 rounded-full text-sm">
                        ดำเนินการสุ่มรางวัล
                    </button>
                    <div class="mt-2 block md:grid grid-cols-2 text-center border border-white">
                        <div>
                            <div class="p-2 border-white text-white bg-gray-900 font-semibold">
                                <div>รางวัลที่ 1</div>
                            </div>
                            <div class="p-2 border text-sm border-white text-white bg-gray-900">
                                <div>รางวัลเลขข้างเคียงรางวัลที่ 1</div>
                            </div>
                            <div class="flex">
                                <div class="w-1/2 p-2 text-sm border-white text-white bg-gray-900">
                                    <div>รางวัลที่ 2</div>
                                </div>
                                <div class="w-1/2 p-2 border-r text-sm font-semibold">
                                    <div id="prize2_1"></div>
                                </div>
                            </div>
                            <div class="p-2 border text-sm border-white text-white bg-gray-900">
                                <div>รางวัลเลขท้าย 2 ตัว</div>
                            </div>
                        </div>

                        <div>
                            <div class="p-2 font-semibold border border-b-0">
                                <div id="prize1"></div>
                            </div>
                            <div class="flex border">
                                <div class="w-1/2 p-2 border-r text-sm">
                                    <div id="prize1_near1"></div>
                                </div>
                                <div class="w-1/2 p-2 text-sm font-semibold">
                                    <div id="prize1_near2"></div>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="w-1/2 p-2 border-r text-sm">
                                    <div id="prize2_2"></div>
                                </div>
                                <div class="w-1/2 p-2 border-r text-sm font-semibold">
                                    <div id="prize2_3"></div>
                                </div>
                            </div>
                            <div class="p-2 border-y border-r text-sm">
                                <div id="paddedPrize1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col border border-blue-950">
                        <div class="p-6 w-full bg-sky-500 text-white font-semibold">
                            ตรวจรางวัลล็อตเตอรี่ Diversition
                        </div>

                        <div class="block md:flex gap-1 m-6">
                            <div>เลขล็อตเตอรี่ :</div>
                            <input id="lotteryInput" class="ml-1 px-2 border border-blue-950 w-96" />
                        </div>
                        <div class="flex flex-col mx-6 my-0 md:m-6">
                            <div id="lotteryResult" class="p-6 font-bold bg-yellow-400 w-full">
                                
                            </div>
                            <button id="checkLotteryButton" class="mb-6 w-52 bg-sky-400 border-gray-700 font-bold mt-6 p-2 border-2 rounded-full text-sm">
                                ตรวจรางวัล
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const url = "{{ env('BACKEND_ENDPOIN') }}";
            window.onload = function() {
                axios.get(url+'/lotterys')
                .then((response) => { 
                    updateLotteryResults(response.data.results);
                })
                .catch((error) => {
                    if(error.response.status == 404) {
                        return
                    }
                    alert("เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง"); 
                });
            }
           
            // Random Botton
            document.addEventListener("DOMContentLoaded", function () {
                document.getElementById("randomizeButton").addEventListener("click", function (event) {
                    event.preventDefault();
                    axios.post(url+'/lotterys')
                        .then((response) => {
                            updateLotteryResults(response.data.results);
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                            alert("เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง"); 
                        });
                    });
            });

            function updateLotteryResults(data) {
                    // Update your HTML with the received data
                    const paddedPrize1 = data.prize1.substring(data.prize1.length - 2);
                    document.getElementById("prize1").innerText = data.prize1; // รางวันที่ 1
                    document.getElementById("prize1_near1").innerText = data.prize1_1 // รางวันไกล้เคียง
                    document.getElementById("prize1_near2").innerText = data.prize1_2
                    
                    document.getElementById("prize2_1").innerText = data.prize2_1; // รางวันที่ 2
                    document.getElementById("prize2_2").innerText = data.prize2_2;
                    document.getElementById("prize2_3").innerText = data.prize2_3;

                    document.getElementById("paddedPrize1").innerText = paddedPrize1; // เลขท้าย
                }

            // Compare Number from input
            document.getElementById('checkLotteryButton').addEventListener('click', function () {
                
                const regex = /^(?=.*[0-9].*[0-9].*[0-9])[0-9a-zA-Z]*$/;

                let inputText = document.getElementById('lotteryInput').value;

                // Validate input
                if (inputText == "") {
                    alert("กรุณาใส่เลขล็อตเตอรี่");
                    return
                }
                if (inputText.length <= 2 || inputText.length >= 4) {
                    alert("กรุณาใส่เลขล็อตเตอรี่ 3 ตัว");
                    return
                }
                if (!regex.test(inputText)) {
                    alert("กรุณาใส่เลขล็อตเตอรี่ 0-9 ตัว");
                    return
                }
                
                // Reset value in result div
                document.getElementById("lotteryResult").innerHTML = "";
                
                

                let prize1 = document.getElementById("prize1").innerText;
                let prize1_near1 = document.getElementById("prize1_near1").innerText;
                let prize1_near2 = document.getElementById("prize1_near2").innerText;
                let paddedPrize1 = document.getElementById("paddedPrize1").innerText;

                let prize2_1 = document.getElementById("prize2_1").innerText;
                let prize2_2 = document.getElementById("prize2_2").innerText;
                let prize2_3 = document.getElementById("prize2_3").innerText;

              
                
                let textResult = [];
                
                if (inputText == prize1) {
                    textResult.push("รางวัลที่ 1");
                    
                } if (
                    inputText == prize1_near1 ||
                    inputText == prize1_near2
                ) {
                    textResult.push("รางวัลไกล้เคียงรางวัลที่ 1");
                }  if (
                    inputText == prize2_1 ||
                    inputText == prize2_2 ||
                    inputText == prize2_3
                ) {
                    textResult.push("รางวัลที่ 2");
                }  if (inputText.substring(inputText.length - 2) == paddedPrize1) {
                    textResult.push("เลขท้าย 2 ตัว");
                } 

                if (textResult.length == 0) {
                    textResult.push("เสียใจด้วยถูกรับประทาน");
                }
                last_text_count = textResult.length;
                textResult.forEach((result, index) => {
                    document.getElementById("lotteryResult").innerHTML += result ;
                    if (index+1 < last_text_count) {
                        document.getElementById("lotteryResult").innerHTML += " และ ";
                    }
                });
            });
        </script>
    </body>
</html>

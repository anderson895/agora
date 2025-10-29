<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Agora App ID Connection Test</title>
</head>
<body>
  <h2>Agora App ID Connection Test</h2>
  <div id="result" style="font-family: monospace; margin-top: 20px;"></div>

  <!-- ✅ Load Agora Web SDK -->
  <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>

  <script>
    const APP_ID = "b2e962fe791e4b23a34dee48010a733f"; // 👈 your Agora App ID
    const resultDiv = document.getElementById("result");

    async function testAgoraConnection() {
      if (!AgoraRTC || !AgoraRTC.createClient) {
        resultDiv.innerHTML = "<p style='color:red'>❌ Agora SDK not loaded properly.</p>";
        return;
      }

      resultDiv.innerHTML = "<p>⏳ Testing Agora connection...</p>";
      console.log("Agora SDK Version:", AgoraRTC.VERSION);

      const client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });
      const testChannel = "testChannel_" + Math.floor(Math.random() * 10000);

      try {
        console.log("Attempting to join test channel:", testChannel);
        await client.join(APP_ID, testChannel, null, null);
        await client.leave();

        console.log("✅ Agora App ID is valid and connection successful!");
        resultDiv.innerHTML = "<p style='color:green'>✅ Agora App ID is valid and connection successful!</p>";

      } catch (error) {
        console.error("❌ Agora connection test failed:", error);

        let message = error.message;

        // interpret the most common Agora errors for clarity
        if (message.includes("dynamic use static key")) {
          message = "Your Agora project requires a Token (secure mode enabled).";
        } else if (message.includes("invalid vendor key")) {
          message = "Invalid App ID — please double-check it in your Agora Console.";
        } else if (message.includes("CAN_NOT_GET_GATEWAY_SERVER")) {
          message = "Agora servers cannot verify your App ID — check if it’s correct or if your network blocks Agora’s domain.";
        }

        resultDiv.innerHTML = `
          <p style='color:red'>❌ Failed to connect to Agora network.</p>
          <pre>${message}</pre>
        `;
      }
    }

    // wait until SDK loads before running test
    window.onload = testAgoraConnection;
  </script>
</body>
</html>

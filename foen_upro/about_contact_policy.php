<?php include 'components/header.php'; ?>

<main class="max-w-4xl mx-auto px-4 py-12 space-y-16 text-gray-800">

  <!-- About Section -->
  <section id="about">
    <h2 class="text-3xl font-bold text-gray-900 mb-4">เกี่ยวกับ Upro</h2>
    <p class="text-gray-700 leading-relaxed">
      Upro คือแพลตฟอร์มค้นหาร้านอาหารที่พัฒนาโดยคนรุ่นใหม่ เพื่อให้คุณสามารถค้นพบร้านอาหารใกล้ตัว
      ร้านโปรด และร้านใหม่ๆ ตามสไตล์ของคุณได้ง่ายและสะดวกที่สุด ไม่ว่าจะเป็นบุฟเฟ่ต์ คาเฟ่ ฟาสต์ฟู้ด
      หรือร้านอาหารท้องถิ่น เรารวบรวมไว้เพื่อคุณ
    </p>
    <p class="mt-2 text-gray-700">
      เรามุ่งมั่นสร้างประสบการณ์ที่ดีที่สุดให้กับผู้ใช้ โดยไม่คิดค่าใช้จ่ายจากร้านอาหารรายเล็ก
      พร้อมเปิดโอกาสให้ธุรกิจท้องถิ่นเติบโตอย่างยั่งยืน
    </p>
  </section>

  <!-- Contact Section -->
  <section id="contact">
    <h2 class="text-3xl font-bold text-gray-900 mb-4">ติดต่อเรา</h2>
    <p class="text-gray-700 mb-4">หากคุณมีคำถาม ข้อเสนอแนะ หรือพบปัญหาในการใช้งาน กรุณาติดต่อทีมงานผ่านช่องทางด้านล่าง</p>
    
    <div class="bg-white rounded-xl shadow-sm border p-6 space-y-4">
      <p><strong>Line:</strong> <a href="https://lin.ee/6iD48hU" class="text-[#f37021] hover:underline">Upro โปรสุดคุ้ม</a></p>

      <!-- Email and Phone -->
      <div class="space-y-3">
        <p class="flex justify-between items-center">
          <span><strong>อีเมล:</strong> 
            <span id="email" class="text-[#f37021] cursor-pointer hover:underline">b2pccnc@gmail.com</span>
          </span>
          <button onclick="copyToClipboard('#email')" class="bg-[#f37021] text-white px-4 py-2 rounded-md hover:bg-orange-500 transition">คัดลอก</button>
        </p>
        <p class="flex justify-between items-center">
          <span><strong>โทรศัพท์:</strong> 
            <span id="phone" class="text-[#f37021] cursor-pointer hover:underline">082-475-7604</span>
          </span>
          <button onclick="copyToClipboard('#phone')" class="bg-[#f37021] text-white px-4 py-2 rounded-md hover:bg-orange-500 transition">คัดลอก</button>
        </p>
      </div>

      <p><strong>เวลาทำการ:</strong> ทุกวัน 09:00 - 18:00 น.</p>
    </div>
  </section>

  <!-- Privacy Policy Section -->
  <section id="privacy">
    <h2 class="text-3xl font-bold text-gray-900 mb-4">นโยบายความเป็นส่วนตัว</h2>
    <p class="text-gray-700 mb-2">
      Upro ให้ความสำคัญกับความเป็นส่วนตัวของผู้ใช้ทุกคน ข้อมูลที่คุณให้กับเราจะถูกใช้เฉพาะเพื่อการปรับปรุงบริการและการใช้งานที่ดีขึ้น
    </p>
    <ul class="list-disc pl-6 space-y-1 text-gray-700">
      <li>เราไม่เปิดเผยข้อมูลส่วนบุคคลให้บุคคลที่สามโดยไม่ได้รับอนุญาต</li>
      <li>ข้อมูลการค้นหาและพิกัดจะถูกใช้เพื่อแสดงร้านใกล้คุณเท่านั้น</li>
      <li>คุณสามารถติดต่อเราเพื่อขอลบข้อมูลของคุณได้ตลอดเวลา</li>
    </ul>
    <p class="mt-4 text-gray-700">
      การใช้งานเว็บไซต์นี้ถือว่าคุณยอมรับนโยบายความเป็นส่วนตัวฉบับนี้
    </p>

    <!-- เพิ่มเนื้อหากฎหมายที่เกี่ยวข้อง -->
    <h3 class="text-2xl font-semibold text-gray-900 mt-8 mb-4">กฎหมายที่เกี่ยวข้องกับเว็บไซต์</h3>
    <p class="text-gray-700 mb-2">
      การเก็บรวบรวมข้อมูลผู้ใช้และการใช้งานเว็บไซต์ของ Upro จะต้องปฏิบัติตามกฎหมายที่เกี่ยวข้อง ซึ่งรวมถึงกฎหมายด้าน **คุ้มครองข้อมูลส่วนบุคคล** และ **การให้บริการทางอินเทอร์เน็ต** ในประเทศไทย เช่น:
    </p>
    <ul class="list-disc pl-6 space-y-1 text-gray-700">
      <li><strong>พระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA):</strong> เว็บไซต์จะต้องได้รับการยินยอมจากผู้ใช้ก่อนการเก็บรวบรวมข้อมูลส่วนบุคคล และต้องจัดเก็บข้อมูลอย่างปลอดภัย</li>
      <li><strong>พระราชบัญญัติว่าด้วยการกระทำความผิดเกี่ยวกับคอมพิวเตอร์ พ.ศ. 2550:</strong> ป้องกันและปราบปรามการกระทำผิดเกี่ยวกับข้อมูลออนไลน์ รวมถึงการเผยแพร่ข้อมูลที่ไม่ถูกต้อง</li>
      <li><strong>ข้อกำหนดของ Google AdSense:</strong> เว็บไซต์ที่แสดงโฆษณาจาก Google จะต้องปฏิบัติตาม **Google AdSense Program Policies** รวมถึงห้ามเผยแพร่เนื้อหาผิดกฎหมายหรือไม่เหมาะสม</li>
    </ul>

    <p class="mt-4 text-gray-700">
      โดยการใช้งานเว็บไซต์นี้ คุณยอมรับว่าคุณจะปฏิบัติตามกฎระเบียบที่เกี่ยวข้องกับการคุ้มครองข้อมูลส่วนบุคคล และข้อกำหนดของ Google
    </p>
  </section>

</main>

<?php include 'components/footer.php'; ?>

<script>
  // ฟังก์ชันคัดลอกข้อมูลจาก element ที่ระบุ
  function copyToClipboard(elementId) {
    var text = document.querySelector(elementId).textContent || document.querySelector(elementId).innerText;
    var tempInput = document.createElement("input");
    document.body.appendChild(tempInput);
    tempInput.value = text;
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);
    alert("คัดลอกแล้ว: " + text);  // แจ้งผู้ใช้ว่าคัดลอกสำเร็จ
  }
</script>

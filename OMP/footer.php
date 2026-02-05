<footer>
  <p>© <?= date('Y') ?> Open Mind | All Rights Reserved</p>
  <p>
    <a href="index.php">Home</a> | 
    <a href="contact.php">Contact</a> | 
    <a href="privacy.php">Privacy</a>
  </p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script>
  AOS.init();
  const toggleBtn = document.getElementById("darkToggle");
  toggleBtn.addEventListener("click", ()=>{
    document.body.classList.toggle("dark-mode");
    const icon = toggleBtn.querySelector("i");
    if(document.body.classList.contains("dark-mode")) icon.classList.replace("fa-moon","fa-sun");
    else icon.classList.replace("fa-sun","fa-moon");
  });
</script>

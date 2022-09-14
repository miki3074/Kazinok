<template>
  <div class="rang">
    <div class="rang-title text-center">
      <h2 class="tx-uppercase tx-semibold">Игровой ранг</h2>
    </div>
    <div class="rang-container">
      <div v-for=" i in 6" class="rang-item" :class="[(($root.user)&&(i === $root.user.current_rang)) ? 'active' : '']">
        <img  :src="'/images/rang-' + i + '.png'"  alt="">
      </div>
    </div>
  </div>
</template>

<script>


  function footerclasschange() {
    var fclass = window.location.href;
    if (fclass.indexOf('rang') == -1) {
      $('.footer:eq(0)').removeClass( 'd-none' );
    } else {
      $('.footer:eq(0)').addClass( 'd-none' );
    }
  }
  $(document).ready(function () {
    footerclasschange();
  });
  window.addEventListener('popstate', function(){footerclasschange()});
 
</script>

<style scoped>

.rang-title {
  margin-bottom: 25px;
}
.rang-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-around;
}

.rang-item {
  width: 45%;
  margin-bottom: 15px;
}

.rang-item.active {
  outline: 1px solid #fbf8f870;
  box-shadow: 0px 0px 4px 3px rgba(246, 246, 246, 0.41);
}
.rang-item img {
  width: 100%;
}

@media (max-width: 768px){
  .rang-item {
    width: 47%;
  }
}

@media (max-width: 575px){
  
  .rang-title {
    margin-bottom: 15px;
  }
  .rang-container{
    width: 100Vw;
    margin-left: -30px;
    justify-content: space-between;
    padding: 10px;
  }
  .rang-item {
    width: 48%;
  }
}

@media (max-width: 374px){
  .rang-item {
    width: 100%;
  }
}
</style>
<template>
  <div class="container-fluid container-main">
    <div class="row mt-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Hier geht schon bald der Vorhang für unsere Homepage auf!</h1>
      </div>
    </div>      
    <div class="row mt-5 d-flex justify-content-center">
      <div class="col-8 col-md-3">
        <a href="https://www.dermussessein.at">
          <img src="/images/plays/dermussessein.png" width="100%"/>
        </a>
      </div>
    </div>     
    <div class="row mt-5">
      <div class="col d-flex justify-content-center text-center">
        <h3>Infos zu unserem derzeitigen Stück gibt es hier</h3>
      </div>
    </div> 
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {};
  },
  methods: {},
  mounted() {},
};
</script>

<style scoped>
h1, h3 {
color: #f39200!important;
}
</style>
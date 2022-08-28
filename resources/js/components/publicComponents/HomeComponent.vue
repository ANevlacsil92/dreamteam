<template>
  <div class="container-fluid container-main">
    <div class="row mt-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Willkommen auf unserer Theaterhomepage!</h1>
      </div>
    </div>
    <a href="https://www.theatercenterforum.com/der-muss-es-sein-reservierung/">
      <div class="row mt-5 mb-5">
        <div class="col d-flex justify-content-center text-center">
          <h3>Hier geht es zur Kartenreservierung</h3>
        </div>
      </div>
    </a>
    <a href="https://www.dermussessein.at">
      <div class="row mt-5 d-flex justify-content-center">
        <div class="col-8 col-md-3 d-flex justify-content-center">
          <img src="/images/plays/dermussessein.png" width="80%" />
        </div>
      </div>
      <div class="row mt-5">
        <div class="col d-flex justify-content-center text-center">
          <h3>Infos zu unserem derzeitigen Stück gibt es hier</h3>
        </div>
      </div>
    </a>
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
h1,
h3 {
  color: #f39200 !important;
}
a:hover {
  text-decoration: none;
}
</style>
<template>
  <div class="container-fluid container-main">
    <!--<div class="row mt-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1><a href="https://shop.eventjet.at/bd543dad-95a8-4088-8792-1f7e4ce75418"><u>Tickets Hier erhältlich!</u></a></h1>
      </div>
    </div>-->
    <div class="row mt-5 ">
      <div class="col d-flex justify-content-center text-center">
        <h2>Wir freuen uns auf unser nächstes Stück "Der süßeste Wahnsinn".</h2>
      </div>
    </div>
    <div class="row mt-5 d-flex justify-content-center" >
      <div class="col-8 col-md-3 d-flex justify-content-center">
        <a href="/produktionen/der-suesseste-wahnsinn">
          <img class="announcement" src="/images/plays/der-suesseste-wahnsinn/cover.jpg" width="80%" />
        </a>
        </div>
    </div>
    <div class="row mt-5">
      <div class="col d-flex justify-content-center text-center">
        <h2>Zu sehen von 19.-22.03.2025 und 25-29.03.2025 , jeweils 19:30, im <a href="https://maps.app.goo.gl/vJrurmL8L2YdJy1R8"><u>Theater Center Forum.</u></a></h2>
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
h1,
h2,
a {
  color: #f39200 !important;
}
a:hover {
  text-decoration: none;
}
</style>
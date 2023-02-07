<template>
  <div class="container-fluid container-main">
    <div class="row mt-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1><a href="/tickets"><u>Tickets Hier erhältlich!</u></a></h1>
      </div>
    </div>
    <div class="row mt-5 ">
      <div class="col d-flex justify-content-center text-center">
        <h2>Wir freuen uns auf die Wiederaufnahme des Stücks "Der muss es sein".</h2>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col d-flex justify-content-center text-center">
        <h2>Zu sehen am 28. und 29. April 2023, jeweils 19:30, im <a href="https://goo.gl/maps/dbbZkUg4A6sLfjek9"><u>Stadtsaal Purkersdorf.</u></a></h2>
      </div>
    </div>
    <a href="https://www.dermussessein.at">
      <div class="row mt-5 d-flex justify-content-center">
        <div class="col-8 col-md-3 d-flex justify-content-center">
          <img src="/images/plays/dermussessein.png" width="80%" />
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
h2,
a {
  color: #f39200 !important;
}
a:hover {
  text-decoration: none;
}
</style>
<template>
  <div class="container-fluid container-main">
    <div class="row mt-3">
      
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Willkommen auf unserer Theaterhomepage!</h1>
      </div>
    </div>
    <div class="row">
      <announcement-component></announcement-component>
    </div>
    <a href="https://www.dermussessein.at">
      <!--<div class="row mt-5 d-flex justify-content-center">
        <div class="col-8 col-md-3 d-flex justify-content-center">
          <img src="/images/plays/dermussessein.png" width="80%" />
        </div>
      </div>-->
      
    </a>
  </div>
</template>

<script>
import AdventCalendarComponent from './AdventCalendarComponent.vue';
export default {
  components: { AdventCalendarComponent },
  props: ["user"],
  data() {
    return {
      activeDoor:null,
    };
  },
  methods: {
    redirect() {
      window.location.href = "/christmas-announcement";
    },
  },
  mounted() {
    
      this.activeDoor = "test",
      $("#adventModal").modal("show");
      $('#adventModal').focus();
  },
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

.btn{
  background-color: #f39200 !important;
  color: white !important;
  border: none !important;
}

.modal-dialog {
  max-width: 400px;
}

.modal-dialog-landscape {
  max-width: 600px!important;
}
</style>
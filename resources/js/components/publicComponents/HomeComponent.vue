<template>
  <div class="container-fluid container-main">
    <div class="row mt-3">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Willkommen auf unserer Theaterhomepage!</h1>
      </div>
    </div>
    
      <div class="row mt-3 mb-3">
        <div class="col d-flex justify-content-center text-center">
          <h3>Danke fürs Zuschauen - Wir kommen wieder! Infos folgen</h3>
        </div>
      </div>
      <div class="row">
      <!-- Modal -->
      <div
        class="modal fade"
        id="adventModal"
        tabindex="-1"
        aria-labelledby="adventModalLabel"
        aria-hidden="true" 
        v-if="activeDoor"
      >
        <div class="modal-dialog" :class="{'modal-dialog-landscape': activeDoor.is_landscape}">
          <div class="modal-content">
            <div class="modal-body"
              style="background-color: #fce8cb;">
              <div class="container">
                <div class="row">
                  <img
                    :src="'/images/ac/' + activeDoor.image"
                    class="img-fluid"
                  >
                </div>
                <div class="row mt-2">
                  <div v-html="activeDoor.text"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <advent-calendar-component></advent-calendar-component>
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
  methods: {},
  mounted() {
      $("#adventModal").modal("show");
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
.modal-dialog {
  max-width: 400px;
}

.modal-dialog-landscape {
  max-width: 600px!important;
}
</style>
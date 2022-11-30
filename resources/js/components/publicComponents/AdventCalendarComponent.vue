<template>
  <div class="container container-calendar" >
    <div v-if="calendar.length>0">
        <div class="row d-flex justify-content-center" v-for="i in 6" :key="i">
          <div class="col-3 pt-4 pb-4 d-flex justify-content-center advent-window" v-on:click="openmodal(calendar[(i * 4 + j - 4)-1])" v-for="j in 4" :key="j">
            {{ calendar[(i * 4 + j - 4)-1].id }}
          </div>
        </div>
      </div>
  </div>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      calendar: [],
    };
  },
  methods: {
    loadData: function(){
      $.ajax({
        url: "/api/advent-calendar",
        type: "GET",
        success: function(data) {
          this.calendar = data;
        }.bind(this)
      });
    },
    openmodal: function (door) {
      console.log(door);
      this.$parent.activeDoor = door;
      $("#adventModal").modal("show");
    },
  },
  mounted() {
    this.loadData();
  },
};
</script>

<style scoped>
.container-calendar {
  background-image: url("/images/plays/dermussessein.png");
  background-size: auto 100%;
  background-position: center;
  background-repeat: no-repeat;
  width: 25%;
}

@media screen and (max-width: 768px) {
  .container-calendar {
    width: 100%;
  }
}

.advent-window {
  background-color: #ffffffB0;
  color: #f39200;
  font-size: 20px;
  font-weight: bold;
  border: 1px solid #f39200;
}

.advent-window:hover {
  cursor: pointer;
  background-color: #ffffffF0;
}
</style>
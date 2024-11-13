<template>
  <div class="container container-main">
    <div class="row mt-5">
      <div class="col p-0 d-flex justify-content-center text-center">
        <h1>Temperatur</h1>
      </div>
    </div>
    <div class="row mt-3 temp-wrapper">
      <div class="solltemp row d-flex align-items-center" v-if="currentSetPoint">
        <div class="col">
          <div class="row">
            <span>Solltemperatur:</span>
          </div>
          <div class="row text-small">
            <span class="">({{ currentSetPoint.ack ? "übermittelt": "wird übermittelt" }})</span>
          </div>
        </div>
        <div class="col">
          <input type="number" id="temperature" name="temperature" min="5" max="30" v-model="currentSetPoint.temperature"/>
        </div>
        <div class="col">
          <button class="btn btn-tmp" @click="setTemperature()" :disabled="refSetPoint==currentSetPoint.temperature" >Setzen</button>
        </div>
      </div>
      <canvas id="temperatureChart" width="600" height="600"></canvas>
    </div>
    <div class="row mt-3 d-flex justify-content-between">
      <button
        v-for="button in buttons"
        :key="button.label"
        @click="from = button.from; to = button.to; getData();"
        class="btn btn-dreamteam"
        :class="{ 'btn-dreamteam-active': button.from === from }"
      >
        {{ button.label }}
      </button>
    </div>
  </div>
</template>

<script>
// import chart.js
import { Chart, registerables } from 'chart.js';

export default {
  props: ["user", "params"],
  data() {
    return {
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      data: null,
      from: null,
      to: null,
      myChart: null,
      currentSetPoint: null,
      refSetPoint: null,
      buttons: [
        {
          label: "Letzte 24 Stunden",
          from: this.moment().subtract(1, "days").format("YYYY-MM-DD HH:mm:ss"),
          to: this.moment().format("YYYY-MM-DD HH:mm:ss"),
        },
        {
          label: "Letzte 12 Stunden",
          from: this.moment().subtract(12, "hours").format("YYYY-MM-DD HH:mm:ss"),
          to: this.moment().format("YYYY-MM-DD HH:mm:ss"),
        },
        {
          label: "Letzte 6 Stunden",
          from: this.moment().subtract(6, "hours").format("YYYY-MM-DD HH:mm:ss"),
          to: this.moment().format("YYYY-MM-DD HH:mm:ss"),
        },
        {
          label: "Letzte 3 Stunden",
          from: null,
          to: null,
        },
        {
          label: "Letzte Stunde",
          from: this.moment().subtract(1, "hours").format("YYYY-MM-DD HH:mm:ss"),
          to: this.moment().format("YYYY-MM-DD HH:mm:ss"),
        },
        {
          label: "Heute",
          from: this.moment().startOf("day").format("YYYY-MM-DD HH:mm:ss"),
          to: this.moment().format("YYYY-MM-DD HH:mm:ss"),
        }
      ]
    };
  },
  methods: {
    createChart: function () {
      // destroy existing chart if it exists
      if (this.myChart) {
        this.myChart.destroy();
      }


      let ctx = document.getElementById("temperatureChart").getContext("2d");
      // labels shoud be the timestamp but without seconds using moment.js with Day and Time
      let labels = this.data.data.map((temp) => temp.created_at);
      let values = this.data.data.map((temp) => temp.temperature);
      let valuesHumidity = this.data.data.map((hum) => hum.humidity);
      
      let setValuesArray = new Array(labels.length).fill(null);

      labels.forEach(label => {
        // get all setPoints where created_at is smaller than the current label
        let setPoint = this.data.setData.find(setPoint => this.moment(setPoint.created_at).isBefore(this.moment(label)));
        if (setPoint) {
          setValuesArray[labels.indexOf(label)] = setPoint.temperature;
        }
      });


      Chart.register(...registerables);

      this.myChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Temperatur (Aktuell: " + this.data.currentTemp.temperature + "°C)",
              data: values,
              borderColor: "rgb(255, 99, 132)",
              borderWidth: 1,
              fill: false,
              yAxisID: 'y',
            },
            {
              label: "Solltemperatur",
              data: setValuesArray,
              // borderColor flatGreen
              borderColor: "rgb(0, 128, 0)",
              borderWidth: 1,
              fill: false,
              yAxisID: 'y',
            },
            {
              label: "Luftfeuchtigkeit (Aktuell: " + this.data.currentHumidity.humidity + "°C)",
              data: valuesHumidity,
              borderColor: "rgb(66, 134, 244)",
              borderWidth: 1,
              fill: false,
              yAxisID: 'y1',
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
                display: true,
                labels: {
                  font: {
                    size: 20,
                  },
                }
            }
          },
          scales: {
            y: {
              type: 'linear',
              display: true,
              position: 'left',
              // min and max values for y-axis
            },
            y1: {
              type: 'linear',
              display: true,
              position: 'right',

              // grid line settings
              grid: {
                drawOnChartArea: false, // only want the grid lines for one axis to show up
              },
            }
          },
        },
      });
    },
    getData: function () {
      let _this = this;

      $.ajax({
        url: "/api/temperature",
        type: "GET",
        data: {
          from: this.from,
          to: this.to,
        },
        success: function (data) {
          _this.data = data;
          _this.currentSetPoint = data.currentSetTemp;
          _this.refSetPoint = data.currentSetTemp.temperature;
          _this.createChart();
        },
      });
    },
    setTemperature: function () {
      let _this = this;

      $.ajax({
        url: "/api/temperature",
        type: "POST",
        headers: {
          "X-CSRF-TOKEN": this.csrf,
        },
        data: {
          temperature: this.currentSetPoint.temperature,
        },
        success: function (data) {
          _this.getData();
        },
      });
    },
  },
  mounted() {
    this.getData();

    // set interval to refresh data every minute
    setInterval(() => {
      this.getData();
    }, 60000);
  },
};
</script>

<style scoped>
h1,
h2,
h3 {
  color: #f39200 !important;
}

p {
  font-size: large;
}

.line-number {
  font-size: smaller;
}

.selected-text {
  background-color: #fff01f;
}

.modal-dialog {
  max-width: 1000px;
}

.temp-wrapper {
  position: relative;
  height: 600px;
  padding-top: 70px;
}

.solltemp {
  position: absolute;
  top: 0;
  right: 0;
  padding: 10px;
  background-color: #f39200;
  color: white;
  border-radius: 5px;
}

.btn-dreamteam {
  background-color: #f39200;
  color: white;
  margin: 5px;
}


.btn-tmp {
  background-color: #86d94f;
  color: white;
  margin: 5px;
}

.btn-dreamteam-active {
  background-color: #f39200;
  color: white;
  box-shadow: 0 0 20px #f39200;
}

.text-small {
  font-size: 12px;
  margin-top: -7px;
}
</style>
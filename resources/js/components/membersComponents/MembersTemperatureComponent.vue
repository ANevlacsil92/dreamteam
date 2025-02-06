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
        @click="mins = button.mins; getData();"
        class="btn btn-dreamteam"
        :class="{ 'btn-dreamteam-active': button.mins === mins }"
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
      mins: 180,
      myChart: null,
      currentSetPoint: null,
      refSetPoint: null,
      buttons: [
        {
          label: "Letzte 24 Stunden",
          mins: 1440,
        },
        {
          label: "Letzte 12 Stunden",
          mins: 720,
        },
        {
          label: "Letzte 6 Stunden",
          mins: 360,
        },
        {
          label: "Letzte 3 Stunden",
          mins: 180,
        },
        {
          label: "Letzte Stunde",
          mins: 60,
        },
        {
          label: "Heute",
          mins:  this.moment().diff(this.moment().startOf('day'), 'minutes'),
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
      let labels = this.data.data.map((temp) => temp.timestamp);
      let values = this.data.data.map((temp) => temp.temperature);
      let valuesHumidity = this.data.data.map((hum) => hum.humidity);
      let setValuesArray = this.data.data.map((temp) => temp.set_temperature);
      let powerStateArray = this.data.data.map((state) => state.power_state);


      Chart.register(...registerables);

      this.myChart = new Chart(ctx, {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Temperatur (Aktuell: " + this.data.data[this.data.data.length-1].temperature + "°C)",
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
              label: "Luftfeuchtigkeit (Aktuell: " + this.data.data[this.data.data.length-1].humidity + "%)",
              data: valuesHumidity,
              borderColor: "rgb(66, 134, 244)",
              borderWidth: 1,
              fill: false,
              yAxisID: 'y1',
            },
            {
              label: "Heizung (Aktuell: " + (parseInt(this.data.data[this.data.data.length-1].power_state) == 1 ? "On" : "Off") + ")",
              data: powerStateArray,
              borderColor: "rgb(255, 211, 42)",
              borderWidth: 1,
              fill: false,
              yAxisID: 'y2',
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
                //,
                //onClick: function(e, legendItem) {
                //  if(legendItem.text == "Solltemperatur" && legendItem.hidden == false) {
                //    legendItem.hidden = true;
                //  
                //    // change the min max of the y axis to the min max of the values array
                //    this.chart.options.scales.y.min = Math.min(...values) - Math.min(...values)/50;
                //    this.chart.options.scales.y.max = Math.max(...values) +  Math.max(...values)/50;
                //    this.chart.update();
                //  }
                //  else if(legendItem.text == "Solltemperatur" && legendItem.hidden == true) {
                //    legendItem.hidden = false;
                //  
                //    // change the min max of the y axis to the min max of the values array and setValuesArray
                //    this.chart.options.scales.y.min = Math.min(...values, ...setValuesArray) - 1;
                //    this.chart.options.scales.y.max = Math.max(...values, ...setValuesArray) + 1;
                //    this.chart.update();
                //  }
                //}
            }
          },
          scales: {
            y: {
              type: 'linear',
              display: true,
              position: 'left',
              // min is the min of valuesset or ValuesArray
              min: Math.min(...values, ...setValuesArray) - 1,
              max: Math.max(...values, ...setValuesArray) + 1,
              
            },
            y1: {
              type: 'linear',
              display: true,
              position: 'right',
              // min is this.data.data.humidity minumum value - 10% and max is this.data.data.humidity maximum value + 10%
              //min: Math.min(...valuesHumidity) - 10,
              //max: Math.max(...valuesHumidity) + 10,

              // grid line settings
              grid: {
                drawOnChartArea: false, // only want the grid lines for one axis to show up
              },
            },
            y2: {
              type: 'linear',
              display: true,
              position: 'right',
              min: -0.2,
              max: 1.2,

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
          mins: this.mins,
        },
        success: function (data) {
          _this.data = data;
          _this.currentSetPoint = data.currentSetTemp;
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
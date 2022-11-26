<script>

export default {
  name: "DigitalClock",
  data() {
    return {
      hours: 0,
      minutes: 0,
      seconds: 0,
      currentdate: "",
      ampm: ""
    }
  },
  mounted() {
    setInterval(() => this.setTime(), 1000)
  },
  methods: {
    setTime() {
      var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      //options.timeZone = 'UTC';
      //options.timeZoneName = 'short';
      let currentdate = new Intl.DateTimeFormat('en-AU', options).format(date);

      const date = new Date();
      
      let hours = date.getHours();
      let minutes = date.getMinutes();
      let seconds = date.getSeconds();
      let ampm = "AM";

      if (hours > 12) {
        hours = hours-12;
        ampm = "PM";
      } else {
        ampm = "AM";
      }
      

      hours = hours <= 9 ? `${hours}`.padStart(2, 0) : hours;
      minutes = minutes <= 9 ? `${minutes}`.padStart(2, 0) : minutes;
      seconds = seconds <= 9 ? `${seconds}`.padStart(2, 0) : seconds;
      this.hours = hours;
      this.minutes = minutes;
      this.seconds = seconds;
      this.ampm = ampm;
      this.currentdate = currentdate;
    }
  }
}
</script>

<template>
            <div class="clock">
              <div class="datetime-content">
                <ul>
                  <li id="hours">{{ hours }}</li>
                  <li id="point1">:</li>
                  <li id="min">{{ minutes }}</li>
                  <li id="point">:</li>
                  <li id="sec">{{ seconds }}</li>
                  <li id="ampm">{{ ampm }}</li>
                </ul>
              </div>
              <div class="datetime-content">
                <div id="Date" class="">{{ currentdate }}</div>
              </div>
            </div>
</template>

<style scoped>

</style>

<template>
  <div>
    <h1>Ram Details of {{ $route.query.assetId }}</h1>
    <RamsList :assetId="$route.query.assetId"/>
  </div>
</template>
<script>

import RamsList from "../components/RamsList";
export default {
  components: {RamsList}
}

</script>
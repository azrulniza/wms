<script setup>
import Floor from '@/views/pages/setting/floor/FloorList.vue';
import Seniority from '@/views/pages/setting/seniority/SeniorityList.vue';
import Position from '@/views/pages/setting/position/PositionList.vue';
import PositionLevel from '@/views/pages/setting/positionlevel/PositionLevelList.vue';
import PositionStatus from '@/views/pages/setting/positionstatus/PositionStatusList.vue';
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="col-12 md:col-12">
                    <h5>Setup</h5>
                    <TabView>
                        <TabPanel header="Floor">
                            <Floor />
                        </TabPanel>
                        <TabPanel header="Seniority">
                            <Seniority />
                        </TabPanel>
                        <TabPanel header="Position">
                            <Position />
                        </TabPanel>
                        <TabPanel header="Position Level">
                            <PositionLevel />
                        </TabPanel>
                        <TabPanel header="Position Status">
                            <PositionStatus />
                        </TabPanel>
                    </TabView>
                </div>
            </div>
        </div>
    </div>
</template>

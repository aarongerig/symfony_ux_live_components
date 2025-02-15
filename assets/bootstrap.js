import { startStimulusApp } from "vite-plugin-symfony/stimulus/helpers";
import Live from "@symfony/ux-live-component";

const app = startStimulusApp();

// 3rd party controllers
app.register('live', Live);

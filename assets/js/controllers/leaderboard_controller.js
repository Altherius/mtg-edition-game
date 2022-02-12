import { Controller} from "@hotwired/stimulus";
import { createApp, compile } from 'vue';
import Leaderboard from '@/pages/leaderboard';


export default class extends Controller {
    connect() {
        const app = createApp(Leaderboard);
        app.mount(this.element);
    }
}
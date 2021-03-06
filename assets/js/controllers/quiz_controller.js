import { Controller} from "@hotwired/stimulus";
import { createApp, compile } from 'vue';
import Quiz from '@/pages/quiz';


export default class extends Controller {
    connect() {
        const app = createApp(Quiz);
        app.mount(this.element);
    }
}
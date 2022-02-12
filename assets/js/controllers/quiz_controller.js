import { Controller} from "@hotwired/stimulus";
import { app } from '../Quiz';

export default class extends Controller {
    connect() {
        app.mount(this.element);
    }
}
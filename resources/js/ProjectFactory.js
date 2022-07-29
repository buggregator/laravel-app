import {Project} from "@/Project";

export default {
    create(json) {
        return new Project(json.id, json.name);
    }
}

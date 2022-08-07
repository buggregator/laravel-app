import {TransactionProject} from "@/TransactionProject";

export class TransactionProjectFactory {
    static create(json) {
        return new TransactionProject(json.projectId, json.transactionId, json.totalEvents, json.projectName, json.transactionName);
    }
}

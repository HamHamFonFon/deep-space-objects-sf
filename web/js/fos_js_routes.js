fos.Router.setData({"base_url":"","routes":{"constellations_list_hem":{"tokens":[["variable","\/","north|south","hem"],["text","\/constellations"]],"defaults":[],"requirements":{"hem":"north|south"},"hosttokens":[],"methods":[],"schemes":[]},"constellation_full":{"tokens":[["variable","\/","[A-Za-z]{3}","id"],["text","\/constellation"]],"defaults":[],"requirements":{"id":"[A-Za-z]{3}"},"hosttokens":[],"methods":[],"schemes":[]},"catalog_list":{"tokens":[["variable","\/","\\w+","catalog"],["text","\/catalog"]],"defaults":[],"requirements":{"catalog":"\\w+"},"hosttokens":[],"methods":[],"schemes":[]},"dso_full":{"tokens":[["variable","\/","[a-zA-Z0-9-+_]+","objectId"],["variable","\/","[a-zA-Z0-9-+_]+","catalog"],["text","\/catalog"]],"defaults":[],"requirements":{"catalog":"[a-zA-Z0-9-+_]+","objectId":"[a-zA-Z0-9-+_]+"},"hosttokens":[],"methods":[],"schemes":[]},"dso_upvote":{"tokens":[["text","\/_upvate\/dso"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"switchlang":{"tokens":[["variable","\/","[^\/]++","language"],["text","\/_switchlang"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]},"search_autocomplete":{"tokens":[["text","\/_autocomplete"]],"defaults":[],"requirements":[],"hosttokens":[],"methods":[],"schemes":[]}},"prefix":"","host":"localhost","scheme":"http"});
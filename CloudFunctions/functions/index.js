const functions = require("firebase-functions");
const admin = require("firebase-admin");
const faker = require("faker");
const cors = require("cors")({ origin: true });

admin.initializeApp();

const firestore = admin.firestore();

exports.createChatDocument = functions.https.onRequest(async (req, res) => {
    try {
        // Parse the incoming JSON data
        const requestData = req.body;

        // Extract data from the request
        const { userIds, postIds, databaseTable, tokens } = requestData;

        const users = [
            {
                id: parseInt(userIds[0]),
                avatar: getRandomAvatar(),
                name: getRandomUserName(),
                token: tokens[0],
            },
            {
                id: parseInt(userIds[1]),
                avatar: getRandomAvatar(),
                name: getRandomUserName(),
                token: tokens[1],
            },
        ];
        // Validate the required data
        if (!userIds || !postIds || !users || !databaseTable) {
            return res
                .status(400)
                .send("Invalid request data. Missing required fields.");
        }

        // Create a new chat document in Firestore
        const chatRef = await firestore.collection("chats").add({
            userIds,
            postIds,
            users,
            databaseTable,
            timestamp: admin.firestore.FieldValue.serverTimestamp(),
        });

        return res
            .status(200)
            .send(`Chat document created with ID: ${chatRef.id}`);
    } catch (error) {
        console.error("Error creating chat document:", error);
        return res.status(500).send("Internal Server Error");
    }
});

function getRandomAvatar() {
    const avatarSize = 200;
    const userId = Math.floor(Math.random() * 1000);
    const avatarSet = ["set1", "set2", "set3"];
    return `https://robohash.org/${userId}?set=${
        avatarSet[Math.floor(Math.random() * avatarSet.length)]
    }&size=${avatarSize}x${avatarSize}`;
}

// Function to generate a random user name using Faker
function getRandomUserName() {
    return faker.name.findName();
}

exports.sendPushNotificationToUser = functions.https.onRequest(
    async (req, res) => {
        cors(req, res, async () => {
            try {
                // Validate that the required parameters are provided
                const { token, title, body } = req.body;

                if (!token || !title || !body) {
                    return res.status(400).json({
                        error: 'Invalid arguments. "token", "title", and "body" are required.',
                    });
                }

                // Create a message object to send to the specified token
                const message = {
                    token,
                    notification: {
                        title,
                        body,
                    },
                };

                // Send the push notification
                await admin.messaging().send(message);

                console.log(
                    `Push notification sent to user with token ${token}`
                );
                return res
                    .status(200)
                    .json({
                        result: `Push notification sent to user with token ${token}`,
                    });
            } catch (error) {
                console.error(error);
                return res
                    .status(500)
                    .json({ error: "Internal server error." });
            }
        });
    }
);
